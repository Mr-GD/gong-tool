<?php

namespace gong\example\Observer;

use gong\tool\base\abs\ObserverAbs;

class AObserver extends ObserverAbs
{

    public $username = '';


    /**
     * 观察者逻辑
     */
    protected function handle()
    {
        echo $this->username;
    }

    /**
     * 触发事件
     */
    protected function triggerEvent()
    {

    }

    /**
     * 前置校验
     */
    protected function verification()
    {

    }

    /**
     * 失败逻辑
     */
    protected function fail()
    {

    }
}