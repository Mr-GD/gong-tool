<?php

namespace common\Controller;

use Illuminate\Routing\Controller;
use Jiannei\Response\Laravel\Response;

class BaseController extends Controller
{

    public function init()
    {

    }

    public function __construct()
    {
        $this->init();
    }

    public function response()
    {
        return (new Response());
    }

    public function getBodyParams()
    {
        return request()->request->all();
    }

    public function getQueryParams()
    {
        return request()->query->all();
    }

    public function getAllParams($key = null, $default = null)
    {
        return request()->input($key, $default);
    }
}
