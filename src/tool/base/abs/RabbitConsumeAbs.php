<?php

namespace gong\tool\base\abs;

use gong\helper\traits\AssignParameter;
use gong\tool\base\api\RabbitMqConsume;
use gong\tool\Rabbitmq\RabbitMq;

abstract class RabbitConsumeAbs implements RabbitMqConsume
{
    use AssignParameter;

    public array $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
        $this->assignParameter($this->params);
    }

    /**
     * 触发事件
     * @param RabbitMq $rabbitMq
     */
    abstract public function triggerEvent(RabbitMq $rabbitMq);
}