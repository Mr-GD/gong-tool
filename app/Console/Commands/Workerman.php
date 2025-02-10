<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

/**
 * @command php82 artisan workerman start
 * 封装一个 channel ，然后在channel 里面写不同的 event，前端声明channel 触发 event，worker自动调用
 * 加一个jwt鉴权进去
 * 剩下就是对应channel里面用户ID，写到redis hash表里
 * 全体的话就单独走个redis list。
 */
class Workerman extends Command
{
    protected $signature = 'workerman {action} {--daemonize}';
    protected $description = 'workerman进程';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        global $argv;//定义全局变量
        $arg     = $this->argument('action');
        $argv[1] = $arg;
        $argv[2] = $this->option('daemonize') ? '-d' : '';//该参数是以daemon（守护进程）方式启动

        // 创建一个Worker监听2345端口，使用websocket协议通讯
        $work                 = new Worker("websocket://0.0.0.0:2345");
        $work->uidConnections = [];//在线用户连接对象
        $work->uidInfo        = [];//在线用户的用户信息
        // 启动4个进程对外提供服务
        $work->count = 4;
        //当启动workerman的时候 触发此方法
        $work->onWorkerStart = function () {
            consoleLine('Workerman Start Successful');
        };

        //当浏览器连接的时候触发此函数
        $work->onConnect = function (TcpConnection $connection) {
            consoleLine('新接入的用户信息:' . json_encode($connection, JSON_UNESCAPED_UNICODE));
        };

        //向用户发送信息的时候触发
        //$connection 当前连接的人的信息 $data 发送的数据
        $work->onMessage = function (TcpConnection $connection, $data) use ($work) {
            consoleLine('接收消息：' . $data);
            $data = json_decode($data, true);
            $this->createUid($connection, $data, $work);
        };

        //浏览器断开链接的时候触发
        $work->onClose = function (TcpConnection $connection) {
            $connection->close();
            consoleLine('关闭连接');
        };

        Worker::runAll();
    }

    //创建uid方法
    public function createUid($connection, $data, Worker $worker)
    {
        $connection->uid = $data['uid'];
        //保存用户的uid
        $worker->connections[$connection->uid] = $connection;
        //向自己的浏览器返回创建成功的信息
        $connection->send("用户:[{$connection->uid}] 创建成功");
    }
}
