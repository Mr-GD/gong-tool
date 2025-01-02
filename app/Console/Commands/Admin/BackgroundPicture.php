<?php

namespace App\Console\Commands\Admin;

use common\Console\BaseCommand;
use common\Tool\ExternalRequest\Vvhan;
use common\Tool\File\SaveLocally;
use common\Tool\File\Upload\KodboxUpload;
use Exception;
use gong\tool\Rabbitmq\RabbitMq;

/**
 * @command php82 artisan admin:grab-background-picture 10000
 */
class BackgroundPicture extends BaseCommand
{
    public int $limit = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:grab-background-picture {limit}';

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
        $this->analyzeParameters();
        consoleLine('------ Start ------');
        $num    = 0;
        $mq     = RabbitMq::instance()
                          ->setExchange(env('FILE_UPLOAD_EXCHANGE'))
                          ->setRoutingKey(env('FILE_UPLOAD_BACKGROUND_ROUTING_KEY'))
                          ->setCloseLink(false)
        ;
        $limit = $this->limit ?: 100;
        while (true) {
            $num++;
            if ($num > $limit) {
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
            sleep(1);
        }
        $mq->close();
        consoleLine('------ End ------');
    }
}
