<?php

namespace App\Console\Commands\AI;

use common\Console\BaseCommand;
use common\Observe\Test\A;
use common\Observe\Test\B;
use common\Tool\ExternalRequest\Alapi;
use common\Tool\ExternalRequest\PracticalToolKit;
use gong\tool\Observer\Action;

/**
 * @command php82 artisan app:test
 */
class Test extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试';

    /**
     * Execute the console command.
     */
    public function handle()
    {

//        $idCard = Alapi::instance()->idCard('511011199507223395');
        $ip = PracticalToolKit::instance()->imageRemoveBg('https://img0.baidu.com/it/u=2798244124,3144261418&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=1200');
        print_r($ip);exit;
    }

    public function sendMessage()
    {

    }
}
