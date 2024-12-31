<?php

namespace App\Console\Commands\Admin;

use common\Tool\ExternalRequest\Vvhan;
use Illuminate\Console\Command;

/**
 * @command php82 artisan admin:grab-background-picture
 */
class BackgroundPicture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:grab-background-picture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取背景图片';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $image = Vvhan::instance()->getLandscapeImages();

    }
}
