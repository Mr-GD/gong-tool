<?php

namespace App\Console\Commands;

use common\Observe\Artisan\ServeStarted;
use common\Tool\File\Upload\KodboxUpload;
use gong\tool\Observer\Action;
use Illuminate\Console\Command;

/**
 * @command php82 artisan basic:initialise-frame
 */
class InitialiseFrame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basic:initialise-frame';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化加载框架配置';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        listen()->register(new ServeStarted())
                ->notify()
        ;
        KodboxUpload::instance()->getAccessToken();
        Action::clear();
    }
}
