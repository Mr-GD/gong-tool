<?php

namespace common\Console\Consume\MessageSend;

use common\tool\MessageSent\ConcreteClass\NotifyFactory;
use gong\tool\base\api\RabbitMqConsume;

class RequestNotifyMessageSend implements RabbitMqConsume
{

    public function consume($data)
    {
        globalVariable()->setVariable('request_id', $data['request_id'] ?? '');
        $notify   = NotifyFactory::create(env('OPEN_REQUEST_NOTICE_TYPE'));
        $messages = sprintf('RequestId:%s 功能:%s 结果:%s',
            $data['request_id'] ?? '',
            $data['features'] ?? '',
            $data['result'] ?? ''
        );
        $notify->generalInformation($messages);
    }
}
