<?php

namespace App\Console\Commands\AI;

use common\Console\BaseCommand;
use common\Constant\Storage\Mode;
use common\Tool\MessageSent\Bark;
use common\Tool\Upload\KodboxUpload;

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
        $file = getcwd() . '/runtime/下载 (18).mp4';
        $url  = KodboxUpload::instance()->simulateFormUpload($file);

        echo formatStorageFileUrl($url, Mode::KODBOX);
    }

    public function sendMessage()
    {
        Bark::instance()->generalInformation('嘿嘿嘿嘿嘿嘿嘿');
    }
}
