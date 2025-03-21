<?php

namespace App\Console\Commands\BasicServices;

use App\Exceptions\ErrException;
use common\Constant\RedisKey;
use common\Exception\Code;
use common\Model\MongoDb;
use common\Model\MysqlModel;
use common\Tool\ExternalRequest\Vvhan;
use gong\tool\Rabbitmq\RabbitMq;
use Illuminate\Console\Command;

/**
 * @command php82 artisan consumer:ip-analyze
 */
class IpAnalyze extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:ip-analyze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'IP解析';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RabbitMq::instance()
                ->setExchange(env('BASIC_SERVICES_EXCHANGE'))
                ->setRoutingKey(env('BASIC_SERVICES_ROUTING_KEY_IP_ANALYZE'))
                ->setQueue(env('BASIC_SERVICES_QUEUE_IP_ANALYZE'))
                ->setRemark('IP解析')
                ->consume([$this, 'handleIpAnalyze'])
        ;
    }

    /**
     *
     * @param $data
     * @return bool
     * @throws ErrException
     * @date 2025/3/21 14:55
     *
     * $data = [
     * [class] => App\Models\MongoDB\OperationLog
     * [id] => 161484808881439810
     * [ip] => 172.19.96.1
     * [request_id] => 111
     * ];
     */
    public function handleIpAnalyze($data)
    {
        variable()->set('request_id', $data['request_id'] ?? '-');

        if (!class_exists($data['class'])) {
            throw new ErrException(Code::DATA_ERROR, '数据错误');
        }

        $analyzeResult = $this->ipHandle($data['ip']);
        if (empty($analyzeResult)) {
            return false;
        }

        $class = new $data['class'];
        if ($class instanceof MongoDb) {
            $this->mongoDb($data, $analyzeResult);
        }

        if ($class instanceof MysqlModel) {
            $this->mysqlModel($data, $analyzeResult);
        }

        return true;
    }

    public function mysqlModel($data, $analyzeResult)
    {
        $class = new $data['class'];
        $class::query()->where('id', $data['id'])->update(['ip_analyze' => sprintf('%s %s', $analyzeResult['prov'] ?? '-', $analyzeResult['city'] ?? '-')]);
    }

    public function mongoDb($data, $analyzeResult)
    {
        $class = new $data['class'];
        $class::original(true)
              ->where('id', $data['id'])
              ->update(['ip_analyze' => sprintf('%s %s', $analyzeResult['prov'] ?? '-', $analyzeResult['city'] ?? '-')])
        ;
    }

    public function ipHandle(string $ip)
    {
        $return = tool()->redis()->hGet(RedisKey::IP_ANALYZE, $ip);
        $return = $return ? json_decode($return, true) : [];
        if (empty($return)) {
            $return = Vvhan::instance()->getIp($ip);
            if (empty($return)) {
                return [];
            }
        }

        tool()->redis()->hSet(RedisKey::IP_ANALYZE, $ip, json_encode($return, JSON_UNESCAPED_UNICODE));

        return $return;
    }
}
