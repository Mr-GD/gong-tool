<?php

namespace App\Http\Controllers\Api;

use common\Controller\ApiController;
use Illuminate\Http\Request;

class TestController extends ApiController
{
    public function index(Request $request)
    {
        echo 'API接口';
    }

    public function checkRequest()
    {
        dd('这是API的POST请求');
    }
}
