<?php

namespace common\Controller;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{

    public function init()
    {

    }

    public function __construct()
    {
        $this->init();
    }
}
