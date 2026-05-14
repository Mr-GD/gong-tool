<?php

namespace gong\tool\base\abs;

use gong\helper\traits\AssignParameter;
use gong\helper\traits\Params;
use gong\tool\base\api\RabbitMqConsume;
use gong\tool\Rabbitmq\RabbitMq;

/**
 * 消费者抽象类
 */
abstract class RabbitConsumeAbs implements RabbitMqConsume
{
    use AssignParameter, Params;

    protected $defaultValues = [];

    public function __construct()
    {
        $this->defaultValues = get_class_vars(static::class);
    }

    public function reset()
    {
        foreach ($this->defaultValues as $property => $value) {
            $this->{$property} = $value;
        }
        return $this;
    }

    public function formatParams()
    {
        $this->assignParameter($this->params);
        return $this;
    }

    /**
     * 触发事件
     * @param RabbitMq $rabbitMq
     */
    abstract public function triggerEvent(RabbitMq $rabbitMq);

}