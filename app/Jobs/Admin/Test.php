<?php

namespace App\Jobs\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Test implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 创建一个新的任务实例.
     */
    public function __construct() {}

    /**
     * 运行任务.
     */
    public function handle(): void
    {
        // 处理上传的 podcast...
    }
}
