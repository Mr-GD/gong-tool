<?php

namespace App\Providers\Artisan;

use App\Listeners\Artisan\CheckServeCommand;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Console\Events\CommandStarting;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        CommandStarting::class => [
            CheckServeCommand::class, // 监听命令执行开始事件
        ],
    ];

}
