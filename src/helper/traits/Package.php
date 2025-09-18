<?php

namespace gong\helper\traits;

trait Package
{
    /**
     * 前置校验
     */
    abstract protected function verification();

    /**
     * 观察者逻辑
     */
    abstract protected function handle();

    /**
     * 失败逻辑
     */
    abstract protected function fail(\Throwable $e);

    /**
     * 触发事件
     */
    abstract protected function triggerEvent();
}