<?php

namespace App\Listeners\Request;

use App\Models\MongoDB\OperationLog;
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
        if ($controller) {
            list($controllerClass, $methodName) = explode('@', $controller);
            // 获取接口注释
            $comment = $this->getMethodComment($controllerClass, $methodName);
//            print_r($comment);
//            exit;
        }
        OperationLog::original(true)
                    ->insert([
                        'request_id'   => globalVariable()->getVariable('request_id'),
                        'url'          => $event->request->getUri(),
                        'api_doc'      => '',
                        'method'       => $event->request->method(),
                        'options'      => json_encode([
                            'query' => $event->request->query->all(),
                            'body'  => $event->request->request->all(),
                        ], JSON_UNESCAPED_UNICODE),
                        'response'     => $event->response->getContent(),
                        'created_at'   => globalVariable()->getVariable('operation_time', time()),
                        'user_type'    => 1,
                        'user_account' => 'admin',
                    ])
        ;
    }

    public function getMethodComment($controllerClass, $methodName)
    {
        $reflection = new \ReflectionMethod($controllerClass, $methodName);
        return $reflection->getDocComment();
    }
}
