<?php

namespace common\Console\Consume\MessageSend;

use common\Tool\MessageSent\ConcreteClass\NotifyFactory;
use gong\tool\base\api\RabbitMqConsume;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestNotifyMessageSend implements RabbitMqConsume
{

    public function consume($data)
    {
        globalVariable()->setVariable('request_id', $data['request_id'] ?? '');
        try {
            $notify = NotifyFactory::create(env('OPEN_REQUEST_NOTICE_TYPE'));
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        $messages = sprintf('RequestId:%s 功能:%s 结果:%s',
            $data['request_id'] ?? '',
            $data['features'] ?? '',
            $data['result'] ?? ''
        );
        $notify->generalInformation($messages);
    }
}
