<?php

namespace gong\tool\base\api;

use gong\tool\Rabbitmq\RabbitMq;

interface RabbitMqConsume
{
    public function consume();

    /**
     * 消费失败
     * @return mixed
     */
    public function fail(RabbitMq $mq, \Throwable $e);
}