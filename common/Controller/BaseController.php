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
}
