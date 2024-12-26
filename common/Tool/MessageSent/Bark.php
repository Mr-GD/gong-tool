<?php

namespace common\Tool\MessageSent;

use common\Tool\Base\Request\InterfaceRequest;
use common\tool\MessageSent\ConcreteClass\NotifySend;
use gong\tool\base\api\Request\MakeRequest;

class Bark extends InterfaceRequest implements MakeRequest, NotifySend
{

    public string $features = 'Bark';

    /**
     * 发送普通消息
     * @param string $message
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2024/12/19 15:16
     */
    public function generalInformation(string $message = '')
    {
        return $this->get()
                    ->setRoute(sprintf('/%s', $message))
                    ->request('发送普通消息')
        ;
    }

    public function setHeaders(): array
    {
        return [];
    }

    public function setUrl(): string
    {
        return env('MESSAGE_SEND_BARK_API_URL') . '/' . env('MESSAGE_SEND_BARK_TOKEN');
    }

    public function analyze($response)
    {
        return $response;
    }
}
