<?php

namespace App\Console\Commands\AI;

use App\Events\Broadcasting\CommonMessage;
use common\Console\BaseCommand;
use common\Constant\Storage\Mode;
use common\Observe\Test\A;
use common\Observe\Test\B;
use common\Tool\File\Upload\KodboxUpload;
use common\Tool\MessageSent\Bark;
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

        CommonMessage::dispatch('这是我的测试数据');

        exit;
        $action = new Action();
        $action->register(new A(18))
               ->register(new B('张三'))
               ->notify()
        ;
    }

    public function sendMessage()
    {

    }
}
