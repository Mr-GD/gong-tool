<?php

namespace common\Tool\Base\Request;

use App\Models\MongoDB\RequestLog;
use common\Tool\MessageSent\ConcreteClass\NotifySend;
use Exception;
use gong\tool\base\abs\Request\MakeRequestAbs;
use gong\tool\Log\Log;
use gong\tool\Rabbitmq\RabbitMq;

abstract class InterfaceRequest extends MakeRequestAbs
{
    public bool $recordLog = false;

    public function afterRequest()
    {
        try {
            $this->recordLogMongo();
            $this->requestNotice();
        } catch (\Exception $e) {
            Log::error('【InterfaceRequest】Error:' . $e->getMessage());
        }
    }

    /**
     * 记录请求日志
     * @author 龚德铭
     * @date 2024/12/20 13:52
     */
    public function recordLogMongo()
    {
        $body = $this->response->getBody();
        $body = json_decode($body, true);
        RequestLog::original(true)
                  ->insert([
                      'request_id' => tool()->value()->getVariable('request_id'),
                      'url'        => $this->url,
                      'features'   => $this->features,
                      'method'     => $this->requestType,
                      'http_code'  => $this->response->getStatusCode(),
                      'options'    => json_encode($this->options, JSON_UNESCAPED_UNICODE),
                      'response'   => json_encode($body, JSON_UNESCAPED_UNICODE),
                      'created_at' => time(),
                      'status'     => $this->response->getStatusCode() === 200 ? RequestLog::STATUS_SUCCESS : RequestLog::STATUS_FAIL,
                      'time_taken' => abs(bcsub($this->requestEndTime, $this->requestStartTime, 3)), //用时
                  ])
        ;
    }

    /**
     * 发送请求
     * @throws Exception
     * @author 龚德铭
     * @date 2024/12/20 14:16
     */
    public function requestNotice()
    {
        if (!env('OPEN_REQUEST_NOTICE')) {
            return;
        }

        if ($this instanceof NotifySend) {
            return;
        }

        RabbitMq::instance()
                ->setExchange(env('REQUEST_EXCHANGE'))
                ->setRoutingKey(env('REQUEST_NOTIFY_RESULT_ROUTING_KEY'))
                ->setRemark('三方请求结果消息通知')
                ->sendMessage([
                    'request_id' => tool()->value()->getVariable('request_id'),
                    'features'   => $this->features,
                    'result'     => $this->response->getStatusCode() === 200 ? '成功' : '失败'
                ])
        ;
    }
}
