<?php

namespace gong\tool\base\abs\Request;

use Exception;
use gong\helper\traits\Data;
use gong\helper\traits\Instance;
use gong\tool\base\api\Request\MakeRequest;
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

    protected string $features = '';

    protected array|string $params = [];

    protected array $headers = [];

    protected array $options = [];

    /** @var Response */
    public Response $response;

    protected Client $client;

    protected string $url = '';
    protected string $completeAddress = '';
    protected string $route = '';

    protected $userDefinedHeader = null;

    protected string $remark = '';

    protected float $requestStartTime;

    protected float $requestEndTime;

    /** @var array 补充options */
    protected array $replenishOptions = [];

    protected string $requestType = 'GET';

    protected int $timeout = 3;

    public bool $recordLog = true;

    protected bool $debug = false;

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

        $this->resetFeatures();
        $this->clear();
        return is_callable($callable) ? $callable($return) : $this->analyze($return);
    }

    protected function unusualNotification()
    {
        try {
            $this->fail();
        } catch (\Throwable $e) {
            $this->log(sprintf('【三方接口请求异常】通知失败 Message: %s', $e->getMessage()));
        }
    }

    protected function resetFeatures()
    {
        $features       = explode('-', $this->features);
        $this->features = reset($features);
    }

    protected function clear()
    {
        $this->params = $this->headers = $this->options = [];
        $this->remark = $this->completeAddress = $this->route = '';

        $this->setClient();
    }

    protected function oneself()
    {
        return new static();
    }

    protected function beforeRequest()
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

    protected function setFormParams(array $formParams = [])
    {
        if (empty($formParams)) {
            return $this;
        }

        $this->options['form_params'] = $formParams;
        return $this;
    }

    protected function assembleGetParams()
    {
        if (empty($this->params)) {
            return false;
        }

        $str       = str_contains($this->url, '?') ? '&' : '?';
        $this->url .= $str . (is_string($this->params) ? $this->params : http_build_query($this->params));

        return true;
    }

    protected function recordLog($response)
    {
        if (!$this->recordLog) {
            return;
        }


        $content = [
            'url'      => $this->url,
            'method'   => $this->requestType,
            'params'   => is_array($this->params) ? json_encode($this->params, JSON_UNESCAPED_UNICODE) : $this->params,
            'headers'  => json_encode($this->headers, JSON_UNESCAPED_UNICODE),
            'response' => json_encode($response, JSON_UNESCAPED_UNICODE)
        ];

        $this->log($content);
    }

    protected function getFeatures()
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

    abstract function log(...$args);
}