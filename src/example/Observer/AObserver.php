<?php

namespace gong\example\Observer;

use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;

class AObserver extends ObserverAbs implements Observer
{

    public $username = '';


    public function watch()
    {
        echo $this->username;exit;
    }
}