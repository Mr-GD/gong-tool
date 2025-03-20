<?php

namespace App\Listeners\Request;

use App\Models\MongoDB\OperationLog;
use common\Constant\RedisKey;
use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * 控制器执行完毕
 */
class ControllerHasFinishedExecuting
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestHandled $event): void
    {
        /** 执行观察者 */
        $this->fireListen();
        $this->recordOperationLog($event);
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
        OperationLog::original(true)
                    ->insert([
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
                    ])
        ;
    }
}
