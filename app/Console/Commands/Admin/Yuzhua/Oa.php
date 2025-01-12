<?php

namespace App\Console\Commands\Admin\Yuzhua;

use common\Tool\MessageSent\Email;
use Illuminate\Console\Command;
use common\Tool\ExternalRequest\Yuzhua\Oa as YuzhuaOa;

/**
 * @command php82 artisan admin:oa
 */
class Oa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:oa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '鱼爪-OA接口请求';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $return = $this->retreats();
//        $return = $this->courseLearningStatistics();
//        $admin = AdminService::instance()
//                             ->setParams([
//                                 'username' => 'username',
//                                 'password' => 'password'
//                             ])
//                             ->create()
//        ;

        exit;
    }

    public function courseLearningStatistics()
    {
        return YuzhuaOa::instance()
                       ->setNumber('10003')
                       ->courseLearningStatistics()
        ;
    }

    /**
     * 离退列表
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2025/1/3 17:31
     */
    private function retreats()
    {
        return YuzhuaOa::instance()
                       ->setToken('7bcf6f30a44522cab4ef20baf576c6d520daf054')
                       ->setPattern('test')
                       ->retreats()
        ;
    }
}
