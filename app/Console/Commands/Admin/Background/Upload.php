<?php

namespace App\Console\Commands\Admin\Background;

use App\Models\Background\LoginBackgroundPicture;
use common\Constant\Storage\Mode;
use common\Tool\File\Upload\KodboxUpload;
use gong\tool\Rabbitmq\RabbitMq;
use Illuminate\Console\Command;

/**
 * @command php82 artisan admin:consume:background:upload
 */
class Upload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:consume:background:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '登陆背景图片上传保存';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RabbitMq::instance()
                ->setExchange(env('FILE_UPLOAD_EXCHANGE'))
                ->setRoutingKey(env('FILE_UPLOAD_BACKGROUND_ROUTING_KEY'))
                ->setQueue(env('FILE_UPLOAD_BACKGROUND_QUE'))
                ->setRemark('登陆背景图片上传保存')
                ->consume(function ($data) {
                    $localUrl = $data['file'] ?? '';
                    if (!$localUrl) {
                        return false;
                    }
                    $imageUrl = KodboxUpload::instance()->simulateFormUpload($localUrl);
                    LoginBackgroundPicture::instance()->insert([
                        'url'          => $imageUrl,
                        'created_at'   => time(),
                        'storage_mode' => Mode::KODBOX, //可道云
                    ]);
                    return true;
                })
        ;
    }
}
