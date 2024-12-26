<?php

namespace App\Console\Commands\AI;

use common\Console\BaseCommand;
use gong\tool\Log\Log;
use JetBrains\PhpStorm\NoReturn;

/**
 * 智谱清言AI
 * https://chatglm.cn/main/alltoolsdetail?lang=zh
 */
class ZhipuAi extends BaseCommand
{

    public $username;
    public $queue;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:zhipu-ai {username} {queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '智谱清言AI';

    /**
     * Execute the console command.
     */
    #[NoReturn] public function handle()
    {
        Log::info('你好啊');
        echo $this->queue;exit;
    }
}
