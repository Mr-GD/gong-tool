<?php

namespace App\Console\Commands\Admin;

use common\Console\BaseCommand;
use common\Tool\ExternalRequest\Vvhan;
use common\Tool\File\SaveLocally;
use Exception;
use gong\tool\Rabbitmq\RabbitMq;

/**
 * @command php82 artisan admin:background-picture 10000
 */
class BackgroundPicture extends BaseCommand
{
    public int $limit = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:background-picture {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取背景图片';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->analyzeParameters();
        consoleLine('------ Start ------');
        $num   = 0;
        $limit = $this->limit ?: 100;
        while (true) {
            $num++;
            if ($num > $limit) {
                break;
            }

            try {
                $remoteUrl = Vvhan::instance()->getLandscapeImages();
                $localUrl  = SaveLocally::instance()->setRemoteUrl($remoteUrl)->execute();
                RabbitMq::instance()
                        ->setExchange(env('FILE_UPLOAD_EXCHANGE'))
                        ->setRoutingKey(env('FILE_UPLOAD_BACKGROUND_ROUTING_KEY'))
                        ->setRemark('获取背景图片')
                        ->sendMessage(['file' => $localUrl]);
                consoleLine(sprintf('【Successful】第%s张图片 Url:%s', $num, $localUrl));
            } catch (Exception $e) {
                consoleLine(sprintf('【Error】第%s张图片 Message:%s', $num, $e->getMessage()));
                continue;
            }
            sleep(1);
        }
        consoleLine('------ End ------');
    }
}
