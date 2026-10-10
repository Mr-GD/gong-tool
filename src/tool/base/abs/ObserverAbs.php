<?php

namespace gong\tool\base\abs;


use gong\helper\traits\AssignParameter;
use gong\helper\traits\Data;
use gong\helper\traits\Make;
use gong\helper\traits\UsageLogs;
use gong\helper\traits\Package;
use gong\tool\base\api\Observer;
use gong\tool\Observer\Action;

/**
 * 观察者抽象类
 * @method Action getAction() 获取Action对象
 * @method $this setAction(Action $action) 设置Action对象
 */
abstract class ObserverAbs implements Observer
{
    use Data, AssignParameter, UsageLogs, Package, Make;

    protected Action $action;

    public array $params = [];

    /**
     * 失败时是否允许队列重试（默认 false，保持原有行为）
     * 子类如需在临时故障时由队列重试，可设为 true
     */
    protected bool $retryable = false;

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
        } catch (\Throwable $e) {
            $this->log(sprintf('[Observer error] %s Message:%s Error:%s', static::class, $e->getMessage(), $e->getTraceAsString()), 'error', $e);
            $this->fail($e);
            if ($this->retryable) {
                throw $e;
            }
            return;
        }

        $this->triggerEvent();
    }

    /**
     * 是否允许重试
     */
    public function isRetryable(): bool
    {
        return $this->retryable;
    }

    /**
     * 序列化时排除 $action（Action 持有 observer 列表，会循环引用），
     * 只序列化 params 及子类属性，减少序列化体积
     */
    public function __sleep(): array
    {
        $properties = [];
        $reflection = new \ReflectionObject($this);
        do {
            foreach ($reflection->getProperties() as $property) {
                $name = $property->getName();
                if ($name === 'action') {
                    continue;
                }
                $properties[] = $name;
            }
        } while ($reflection = $reflection->getParentClass());

        return array_unique($properties);
    }
}