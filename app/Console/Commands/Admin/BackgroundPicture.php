<?php

namespace App\Console\Commands\Admin;

use App\Models\Background\LoginBackgroundPicture;
use common\Tool\ExternalRequest\Vvhan;
use common\Tool\File\SaveLocally;
use common\Tool\File\Upload\KodboxUpload;
use Exception;
use gong\tool\Rabbitmq\RabbitMq;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        consoleLine('------ Start ------');
        $num    = 0;
        $mq     = RabbitMq::instance()
                          ->setExchange(env('FILE_UPLOAD_EXCHANGE'))
                          ->setRoutingKey(env('FILE_UPLOAD_BACKGROUND_ROUTING_KEY'))
                          ->setCloseLink(false)
        ;
        while (true) {
            $num++;
            if ($num > 100) {
                break;
            }

            try {
                $remoteUrl = Vvhan::instance()->getLandscapeImages();
                $localUrl  = SaveLocally::instance()->setRemoteUrl($remoteUrl)->execute();
                $mq->sendMessage(['file' => $localUrl]);
                consoleLine(sprintf('【Successful】第%s张图片 Url:%s', $num, $localUrl));
            } catch (Exception $e) {
                consoleLine(sprintf('【Error】第%s张图片 Message:%s', $num, $e->getMessage()));
                continue;
            }
        }
        $mq->close();
        consoleLine('------ End ------');
    }
}
