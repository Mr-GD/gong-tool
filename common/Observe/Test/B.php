<?php

namespace common\Observe\Test;

use gong\tool\base\abs\ObserverAbs;
use gong\tool\base\api\Observer;

class B extends ObserverAbs implements Observer
{

    public string $name;

    public function watch()
    {
        echo $this->name;exit;
    }
}
