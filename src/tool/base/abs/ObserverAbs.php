<?php

namespace gong\tool\base\abs;


use gong\helper\traits\AssignParameter;
use gong\helper\traits\Data;
use gong\helper\traits\Log;
use gong\helper\traits\Package;
use gong\tool\base\api\Observer;
use gong\tool\Observer\Action;

/**
 *观察者抽象类
 * @method Action getAction() 获取Action对象
 * @method $this setAction(Action $action) 设置Action对象
 */
abstract class ObserverAbs implements Observer
{
    use Data, AssignParameter, Log, Package;

    protected Action $action;

    public array $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
        $this->assignParameter($params);
    }

    public function watch()
    {
        $this->verification();

        try {
            $this->handle();
        }catch (\Throwable $e) {
            $this->log($e->getMessage());
            $this->fail($e);
            return;
        }

        $this->triggerEvent();
    }
}