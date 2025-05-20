<?php

namespace gong\tool\base\abs;

use gong\helper\traits\AssignParameter;
use gong\tool\base\api\RabbitMqConsume;

abstract class RabbitConsumeAbs implements RabbitMqConsume
{
    use AssignParameter;

    public array $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
        $this->assignParameter($this->params);
    }

    public function fail(\gong\tool\Rabbitmq\RabbitMq $mq, \Throwable $e)
    {

    }
}