<?php

namespace gong\tool\Rabbitmq;

use Exception;
use gong\helper\traits\Data;
use gong\helper\traits\Instance;
use gong\helper\traits\Log;
use gong\tool\base\api\RabbitMqConsume;

/**
 * @method $this setExchange(string $exchange) 设置交换机
 * @method $this setQueue(string $queue) 设置队列
 * @method $this setRoutingKey(string $routingKey) 设置路由键
 * @method $this setRemark(string $remark) 设置备注
 * @method $this setHost(string $host) 设置主机
 * @method $this setPort(int $port) 设置端口
 * @method $this setLogin(string $login) 设置用户名
 * @method $this setPassword(string $password) 设置密码
 * @method $this setVhost(string $vhost) 设置虚拟主机
 * @method $this setHeartbeat(int $heartbeat) 设置心跳
 * @method $this setCloseLink(bool $isClose) 关闭连接
 * @method mixed getData() 获取数据
 */
class RabbitMq
{
    use Data, Instance, Log;

    /** 交换机 */
    protected $exchange;
    /** 队列 */
    protected $queue;
    protected $data;

    protected $host;
    protected $port;
    protected $login;
    protected $password;
    protected $vhost;
    protected $heartbeat = 60;
    protected $routingKey = null;

    protected $remark = '';
    protected $closeLink = true;

    /** @var \AMQPConnection */
    private $_connection;
    /** @var \AMQPChannel */
    private $_channel;
    /** @var \AMQPExchange */
    private $_connectionExchange;
    /** @var \AMQPQueue */
    private $_connectionQueue;

    /** @var int 消费者最大重连次数 */
    private $_maxReconnectAttempts = 5;

    /** @var int 当前重连次数 */
    private $_reconnectAttempts = 0;

    public $config = [];

    const SETTING_KEY = [
        'host', 'port', 'login', 'password', 'vhost',
    ];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 发送消息
     * @param array $pushData
     * @return bool
     * @date 2024/9/29 17:29
     */
    public function sendMessage(array $pushData = [])
    {
        $message = sprintf('【生产者】【%s】Ex:%s Que: %s RoutingKey: %s',
            $this->remark ?: '-',
            $this->exchange,
            $this->queue ?: '-',
            $this->routingKey ?: '-'
        );
        try {
            $this->_connection();
            $pushData = isManyArray($pushData) ? $pushData : [$pushData];
            foreach ($pushData as $pv) {
                $pv = json_encode($pv, JSON_UNESCAPED_UNICODE);
                $this->_connectionExchange->publish($pv, $this->routingKey);
                $this->log("{$message} 推送数据：" . $pv);
            }
        } catch (Exception $e) {
            $this->log($message . ' Error:' . $e->getMessage());
        } finally {
            if ($this->closeLink) {
                $this->close();
            }
        }
        return true;
    }

    /**
     * 关闭连接
     * @return bool
     * @date 2024/10/8 14:04
     */
    public function close()
    {
        if (!empty($this->_channel)) {
            $this->_channel->close();
        }
        if (!empty($this->_connection)) {
            $this->_connection->disconnect();
        }

        return true;
    }

    /**
     * 消费者
     * @date 2024/9/29 17:44
     */
    public function consume($callback)
    {
        while (true) {
            try {
                $this->_connection();
                if (empty($this->_connectionQueue)) {
                    throw new \AMQPConnectionException('链接失败');
                }
                $this->_connectionQueue->consume(function (\AMQPEnvelope $envelope, \AMQPQueue $queue) use ($callback) {
                    $recordMessage = sprintf('【消费者】【%s】Ex:%s Que: %s RoutingKey: %s',
                        $this->remark ?: '-',
                        $this->exchange,
                        $this->queue,
                        $this->routingKey ?: '-'
                    );
                    // 从信封获取数据
                    $msg = $envelope->getBody();
                    $this->log("{$recordMessage} 获取消息：" . $msg);
                    consoleLine("{$recordMessage} 获取消息：" . $msg);
                    // 从信封获取数据的唯一标识
                    $envelopeID = $envelope->getDeliveryTag();

                    /************ 处理业务逻辑 start **********/
                    $this->data = $data = is_string($msg) ? json_decode($msg, true) : $msg;
                    try {
                        if (is_callable($callback)) {
                            $callback($data);
                        } else {
                            $callback = new $callback($data);
                            if ($callback instanceof RabbitMqConsume) {
                                try {
                                    $callback->consume();
                                } catch (\Throwable $e) {
                                    $callback->fail($this, $e);
                                    throw $e;
                                }
                            }
                        }

                        //ack机制（消息确认机制，告知RabbitMQ数据已接收处理完成，可以安全删除）
                        $queue->ack($envelopeID);
                    } catch (\Throwable $e) {
                        $queue->ack($envelopeID);
                        $this->log("{$recordMessage} Error:" . $e->getMessage() . ' params:' . $msg);
                        consoleLine("{$recordMessage} Error:" . $e->getMessage() . ' params:' . $msg);
                    }
                    /************ 处理业务逻辑 end **********/
                });
            } catch (\AMQPConnectionException $e) {
                // 专门处理 RabbitMQ 连接问题
                $this->_reconnectAttempts++;
                $consoleMessage = sprintf("RabbitMQ 连接失败，错误信息：%s，尝试重新连接 (%s / %s)...",
                    $e->getMessage(),
                    $this->_reconnectAttempts,
                    $this->_maxReconnectAttempts
                );
                consoleLine($consoleMessage);
                sleep(5); // 等待5秒再尝试重连

                if ($this->_reconnectAttempts >= $this->_maxReconnectAttempts) {
                    $this->log(sprintf("【Que】%s 消费者 重连失败已达上限 (%s 次)，退出消费者", $this->queue, $this->_maxReconnectAttempts));
                    consoleLine("RabbitMQ 重连失败已达上限 ({$this->_maxReconnectAttempts} 次)，退出消费者。");
                    $this->close();
                    break;
                }
            } catch (\AMQPChannelException $e) {
                // 专门处理 RabbitMQ 通道问题
                $this->log(sprintf("【Que】%s 消费者通道异常：%s", $this->queue, $e->getMessage()));
                consoleLine("RabbitMQ 通道异常：" . $e->getMessage());
                break; // 如果是通道问题，可以选择直接退出消费者
            } catch (\Throwable $e) {
                // 其他未预见的异常
                $this->log(sprintf("【Que】%s 消费者未知异常：%s", $this->queue, $e->getMessage()));
                consoleLine("RabbitMQ 未知异常：" . $e->getMessage());
                break; // 其他异常是否退出需要根据具体需求调整
            } finally {
                // 关闭通道和连接
                $this->close();
            }
        }
    }

    /**
     * 建立连接
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     * @date 2024/9/29 17:39
     */
    private function _connection()
    {
        if ($this->_connection) {
            return;
        }
        $this->_setConfig();

        // 建立连接
        $this->_connection = new \AMQPConnection(['heartbeat' => $this->heartbeat ?: 60]);
        $this->_connection->setHost($this->host);
        $this->_connection->setPort($this->port);
        $this->_connection->setLogin($this->login);
        $this->_connection->setPassword($this->password);
        $this->_connection->setVhost($this->vhost);
        $this->_connection->connect();

        // 建立通道
        $this->_channel = new \AMQPChannel($this->_connection);
        $this->_channel->qos(0, 1);
        // 定义交换器
        $this->_connectionExchange = new \AMQPExchange($this->_channel);
        // 交换器名称
        $this->_connectionExchange->setName($this->exchange);
        // 交换器类型（直连）
        $this->_connectionExchange->setType(AMQP_EX_TYPE_DIRECT);
        // 交换器标签
        $this->_connectionExchange->setFlags(AMQP_DURABLE);
        // 创建交换器
        $this->_connectionExchange->declareExchange();

        if ($this->queue) {
            // 定义队列
            $this->_connectionQueue = new \AMQPQueue($this->_channel);
            // 队列名称
            $this->_connectionQueue->setName($this->queue);
            // 队列标签
            $this->_connectionQueue->setFlags(AMQP_DURABLE);
            // 创建队列
            $this->_connectionQueue->declareQueue();
            // 绑定交换器
            $this->_connectionQueue->bind($this->exchange, $this->routingKey);
        }
    }

    private function _setConfig()
    {
        foreach ($this->config as $settingKey => $value) {
            $this->{$settingKey} = $value;
        }

        $settingKey = array_keys($this->config);
        $diff       = array_diff(self::SETTING_KEY, $settingKey);
        foreach ($diff as $dv) {
            $key         = 'AMQP_' . strtoupper($dv);
            $this->{$dv} = env($key);
        }
    }
}