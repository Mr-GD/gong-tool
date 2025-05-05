<?php

namespace gong\example\Observer;

use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;

class BObserver extends ObserverAbs implements Observer
{
    public $age = 0;

    public function __construct(int $age = 1)
    {
        $this->age = $age;
    }
    
    public function watch()
    {
        // TODO: Implement watch() method.
    }
}