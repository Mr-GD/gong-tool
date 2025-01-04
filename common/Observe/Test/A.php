<?php

namespace common\Observe\Test;

use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;

class A extends ObserverAbs implements Observer
{

    public string $age;

    public function watch()
    {
        echo $this->age;exit;
    }
}
