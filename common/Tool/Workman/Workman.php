<?php

namespace common\Tool\Workman;

use common\Tool\Base\Request\InterfaceRequest;

class Workman extends InterfaceRequest
{

    public function sendOrdinaryMessage()
    {
        return $this->post()
                    ->setRoute(':1236')
                    ->setRemark('workman-普通消息')
                    ->request()
        ;
    }

    public function setHeaders(): array
    {
        return [];
    }

    public function setUrl(): string
    {
        return str_replace(['http://', 'https://'], 'ws://', env('PROJECT_API_URL'));
    }

    public function analyze($response)
    {
        // TODO: Implement analyze() method.
    }
}
