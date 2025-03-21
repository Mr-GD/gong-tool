<?php

namespace common\Observe\BasicServices;

use gong\tool\base\abs\ObserverAbs;
use gong\tool\Rabbitmq\RabbitMq;

class ipAnalyze extends ObserverAbs
{

    public $class;
    public $id;
    public $ip;

    public function watch()
    {
        if (empty($ip)) {
            return ;
        }

        RabbitMq::instance()
                ->setExchange(env('BASIC_SERVICES_EXCHANGE'))
                ->setRoutingKey(env('BASIC_SERVICES_ROUTING_KEY_IP_ANALYZE'))
                ->setRemark('IP解析')
                ->sendMessage([
                    'class' => $this->class,
                    'id'    => $this->id,
                    'ip'    => $this->ip,
                ])
        ;
    }
}
