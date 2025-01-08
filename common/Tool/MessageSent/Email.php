<?php

namespace common\Tool\MessageSent;

use common\Tool\Base\Request\InterfaceRequest;

class Email extends InterfaceRequest
{

    public function setHeaders(): array
    {
        return [];
    }

    public function setUrl(): string
    {
        return '';
    }

    public function analyze($response)
    {
        return $response;
    }
}
