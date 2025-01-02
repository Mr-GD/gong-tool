<?php

namespace App\Console\Commands;

use common\Console\BaseCommand;
use gong\tool\Rabbitmq\RabbitMq;

/**
 * @command php82 artisan app:request-notify-message-send
 */
class RequestNotifyMessageSend extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:request-notify-message-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '异步消息发送';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RabbitMq::instance()
                ->setExchange(env('REQUEST_EXCHANGE'))
                ->setRoutingKey(env('REQUEST_NOTIFY_RESULT_ROUTING_KEY'))
                ->setQueue(env('REQUEST_NOTIFY_RESULT_QUEUE'))
                ->consume(new \common\Console\Consume\MessageSend\RequestNotifyMessageSend())
        ;
    }
}
