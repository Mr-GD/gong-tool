<?php

namespace gong\example\Rabbitmq;

use gong\tool\Rabbitmq\RabbitMq;

class SendTest
{
    public function publish()
    {
        $rabbitMq = RabbitMq::instance()
                            ->setExchange(env('STAFF_EXCHANGE'))
                            ->setQueue(env('STAFF_JOB_GRADE_IMPORT_QUE'))
                            ->setRoutingKey(env('STAFF_JOB_GRADE_IMPORT_ROUTING_KEY'))
                            ->setCloseLink(false)
                            ->setRemark('员工职位等级导入')
        ;

        $pushData = [
            1, 2, 3, 4, 5
        ];
        foreach ($pushData as $data) {
            $rabbitMq->sendMessage(['id' => $data]);
        }

        return $rabbitMq->close();
    }

    public function useConsume()
    {
        RabbitMq::instance()
                ->setExchange(env('STAFF_EXCHANGE'))
                ->setQueue(env('STAFF_JOB_GRADE_IMPORT_QUE'))
                ->setRoutingKey(env('STAFF_JOB_GRADE_IMPORT_ROUTING_KEY'))
                ->setRemark('员工职位等级导入')
                ->consume(TestConsume::class)
        ;
    }
}