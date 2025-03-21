<?php

namespace App\Listeners\Request;

use App\Models\MongoDB\OperationLog;
use common\Constant\RedisKey;
use common\Observe\BasicServices\ipAnalyze;
use gong\tool\Log\Log;
use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * 控制器执行完毕
 */
class ControllerHasFinishedExecuting
{
    public string $snowflakeId;
    public mixed $ip;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->snowflakeId = snowflakeId();
        $this->ip          = getIp();
    }

    /**
     * Handle the event.
     */
    public function handle(RequestHandled $event): void
    {
        try {
            $this->recordOperationLog($event);
        } catch (\Exception $e) {
            Log::error('[ControllerHasFinishedExecuting] msg:' . $e->getMessage());
        }

        /** 执行观察者 */
        $this->fireListen();
    }

    public function fireListen()
    {
        listen()->notify();
        listen()->clearResult();
    }

    public function recordOperationLog(RequestHandled $event)
    {
        // 获取请求的路由信息
        $action     = $event->request->route()->getAction();
        $controller = $action['controller'] ?? null;
        $docComment = $controller ? tool()->redis()->hGet(RedisKey::API_DOCS, $controller) : $controller;
        OperationLog::original(date('Y-m'))
                    ->insert([
                        'id'           => $this->snowflakeId,
                        'request_id'   => tool()->value()->get('request_id'),
                        'url'          => $event->request->getUri(),
                        'api_doc'      => $docComment ?: $controller,
                        'method'       => $event->request->method(),
                        'options'      => json_encode([
                            'query' => $event->request->query->all(),
                            'body'  => $event->request->request->all(),
                        ], JSON_UNESCAPED_UNICODE),
                        'response'     => $event->response->getContent(),
                        'created_at'   => tool()->value()->get('operation_time', time()),
                        'user_type'    => 1,
                        'user_account' => 'admin',
                        'ip'           => $this->ip,
                    ])
        ;
        listen()->register(new ipAnalyze([
            'class'      => OperationLog::class,
            'id'         => $this->snowflakeId,
            'ip'         => $this->ip,
            'request_id' => tool()->value()->get('request_id'),
        ]));
    }
}
