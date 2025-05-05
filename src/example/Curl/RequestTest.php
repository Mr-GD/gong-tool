<?php

namespace gong\example\Curl;

use gong\tool\base\abs\Request\MakeRequestAbs;
use gong\tool\base\api\Request\MakeRequest;

class RequestTest extends MakeRequestAbs implements MakeRequest
{


    public function setHeaders(): array
    {
        return [

        ];
    }

    public function setUrl(): string
    {
        return 'https://api.vvhan.com';
    }

    public function analyze($response)
    {
        return $response;
    }
}