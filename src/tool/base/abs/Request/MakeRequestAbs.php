<?php

namespace gong\tool\base\abs\Request;

use Exception;
use gong\helper\traits\Data;
use gong\helper\traits\Instance;
use gong\tool\base\api\Request\MakeRequest;
use gong\tool\Log\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;

/**
 * @method $this setFeatures(string $features) //设置功能点
 * @method $this setParams($params) //设置请求参数
 * @method $this setTimeout(int $timeout) //设置超时时间
 * @method $this setRoute(string $route) //设置路由
 * @method $this setReplenishOptions(array $replenishOptions) //补充options
 * @method $this setRemark(string $remark) //设置备注
 * @method $this setCompleteAddress(string $completeAddress) //设置完整请求地址 域名 + 路由
 * @method $this setUserDefinedHeader(array $headers) //设置自定义头信息 注意：设置之后就不能再使用setHeader的结果了
 * @method $this setDebug(bool $debug) //设置调试模式
 */
abstract class MakeRequestAbs implements MakeRequest
{
    use Instance, Data;

    public string $features = '';

    public array|string $params = [];

    public array $headers = [];

    public array $options = [];

    /** @var Response */
    public Response $response;

    public Client $client;

    public string $url = '';
    public string $completeAddress = '';
    public string $route = '';

    public $userDefinedHeader = null;

    public string $remark = '';

    public float $requestStartTime;

    public float $requestEndTime;

    /** @var array 补充options */
    public array $replenishOptions = [];

    public string $requestType = 'GET';

    public int $timeout = 3;

    public bool $recordLog = true;

    public bool $debug = false;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function setClient()
    {
        $this->client = new Client([
            'timeout' => $this->timeout,
            'debug'   => $this->debug,
        ]);
    }

    public function request(?callable $callable = null)
    {
        $this->beforeRequest();
        $this->getFeatures();
        $this->requestStartTime = millisecond();
        $this->response         = $this->client->request($this->requestType, $this->url, $this->options);
        $this->requestEndTime   = millisecond();
        $return                 = $this->response->getBody();
        $return                 = json_decode($return, true);

        $this->afterRequest();
        $this->recordLog($return);

        if ($this->response->getStatusCode() != 200) {
            $this->unusualNotification();
            throw new Exception('接口请求失败');
        }

        try {
            $return = is_callable($callable) ? $callable($return) : $this->analyze($return);
        } catch (\Exception $e) {
            $this->exceptionNotify();
        }
        $this->resetFeatures();
        $this->clear();
        return $return;
    }

    public function unusualNotification()
    {
        try {
            $this->fail();
        } catch (\Throwable $e) {
            Log::error(sprintf('【三方接口请求异常】通知失败 Message: %s', $e->getMessage()));
        }
    }

    public function resetFeatures()
    {
        $features       = explode('-', $this->features);
        $this->features = reset($features);
    }

    public function clear()
    {
        $this->params = $this->headers = $this->options = [];
        $this->remark = $this->completeAddress = $this->route = '';

        $this->setClient();
    }

    public function oneself()
    {
        return new static();
    }

    public function beforeRequest()
    {
        // 添加请求方法验证
        if (empty($this->requestType)) {
            throw new InvalidArgumentException('Request method must be set before making a request');
        }
        $this->setClient();

        $this->headers = $this->userDefinedHeader !== null ? $this->userDefinedHeader : $this->setHeaders();
        $this->url     = $this->completeAddress ?: ($this->setUrl() . $this->route);
        $body          = $this->params;
        if ($this->requestType == 'GET') {
            $body = [];
            $this->assembleGetParams();
        }

        if (!empty($this->headers)) {
            $this->options['headers'] = $this->headers;
        }

        if (!empty($this->replenishOptions)) {
            $this->options = array_merge($this->options, $this->replenishOptions);
        }

        if (!empty($body)) {
            $this->options['body']                    = json_encode($body, JSON_UNESCAPED_UNICODE);
            $this->options['headers']['Content-Type'] = 'application/json;charset=utf-8';
        }

        $this->setFormParams();
    }

    public function setFormParams(array $formParams = [])
    {
        if (empty($formParams)) {
            return $this;
        }

        $this->options['form_params'] = $formParams;
        return $this;
    }

    public function assembleGetParams()
    {
        if (empty($this->params)) {
            return false;
        }

        $str       = str_contains($this->url, '?') ? '&' : '?';
        $this->url .= $str . (is_string($this->params) ? $this->params : http_build_query($this->params));

        return true;
    }

    public function recordLog($response)
    {
        if (!$this->recordLog) {
            return;
        }

        $content = sprintf(
            '【%s】Url:%s Method:%s Params:%s Headers:%s Response:%s',
            $this->features,
            $this->url,
            $this->requestType,
            is_array($this->params) ? json_encode($this->params, JSON_UNESCAPED_UNICODE) : $this->params,
            json_encode($this->headers, JSON_UNESCAPED_UNICODE),
            json_encode($response, JSON_UNESCAPED_UNICODE)
        );

        Log::info($content);
    }

    public function getFeatures()
    {
        $this->features          = $this->features ?: static::class;
        $this->features          .= $this->remark ? '-' . $this->remark : '';
        $this->userDefinedHeader = null;
    }

    public function debug(?callable $callback)
    {
        $callback($this);
    }

    public function get()
    {
        $this->requestType = 'GET';
        return $this;
    }

    public function post()
    {
        $this->requestType = 'POST';
        return $this;
    }

    public function put()
    {
        $this->requestType = 'PUT';
        return $this;
    }

    public function delete()
    {
        $this->requestType = 'DELETE';
        return $this;
    }

}