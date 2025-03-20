<?php

namespace common\Console\Consume\MessageSend;

use common\Tool\MessageSent\ConcreteClass\NotifyFactory;
use gong\tool\base\abs\RabbitConsumeAbs;
use gong\tool\base\api\RabbitMqConsume;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestNotifyMessageSend extends RabbitConsumeAbs
{
    public string $requestId;

    public string $features;

    public string $result;

    public function consume()
    {
        tool()->value()->set('request_id', $this->requestId ?: '');
        try {
            $notify = NotifyFactory::create(env('OPEN_REQUEST_NOTICE_TYPE'));
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        $messages = sprintf('RequestId:%s 功能:%s 结果:%s',
            $this->requestId,
            $this->features,
            $this->result
        );
        $notify->generalInformation($messages);
    }
}
