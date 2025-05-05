<?php

namespace gong\example\Rabbitmq;

use gong\tool\base\api\RabbitMqConsume;

class TestConsume implements RabbitMqConsume
{

    public function consume($data)
    {
        var_dump($data);
    }
}