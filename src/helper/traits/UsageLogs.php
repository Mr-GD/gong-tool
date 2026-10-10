<?php

namespace gong\helper\traits;

use gong\tool\Log\Log;

trait UsageLogs
{

    /**
     * 记录日志
     *
     * 声明为 public：使用本 trait 的类同时 use 了 Data 的 __call()，
     * protected 的方法被类外调用（如消费者进程里的 $ctx->log()）不会报错，
     * 而是落到 __call 里被当成属性读写静默吞掉，日志直接丢失。
     *
     * LOGGER 由宿主注册为日志类字符串，契约是 $logger::make($name)->$level($message, $e)。
     * $name 对包内实现是日志目录，对宿主是日志通道名；类未定义 getLogCatalogue() 时传空串，
     * 由宿主按自己的上下文（如请求级 RequestContext）决定落点。
     *
     * @param $message
     * @param string $level info、error、debug、warning
     * @param \Throwable|null $e
     */
    public function log($message, string $level = 'info', ?\Throwable $e = null)
    {
        $logger = variable()->get('LOGGER');
        if (empty($logger)) {
            throw new \Exception('LOGGER is not exist');
        }

        // Make::make() 透传 func_get_args()，空通道必须走无参分支，否则日志类会把 '' 当成通道名
        $instance = $logger::make();
        if (!method_exists($instance, $level)) {
            throw new \Exception(sprintf('Logger [%s] does not support level [%s]', get_class($instance), $level));
        }

        $instance->$level($message, $e);
    }
}