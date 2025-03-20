<?php

namespace App\Exceptions;


use common\Exception\Status;
use Exception;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler
{

    /**
     * A list of the exception types that are not reported.
     *不做日志记录的异常错误
     * @var array
     */
    protected array $dontReport = [
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *认证异常时不被flashed的数据
     * @var array
     */
    protected array $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function __construct(public Exceptions $excepHandler)
    {
    }

    public function handle()
    {
        $this->excepHandler->dontReport($this->dontReport);
        $this->excepHandler->dontFlash($this->dontFlash);

        // 异常处理
        $this->excepHandler->render(function (Throwable $e) {
            //验证器异常统一处理
            $statusCode = 200;
            switch (true) {
                case $e instanceof ValidationException: //验证器类型异常
                    $msg = array_values($e->errors())[0][0] ?? '';
                    break;
                case $e instanceof HttpException:
                    $statusCode = $e->getStatusCode() ? (string)$e->getStatusCode() : '0';
                    $msg        = Status::getReasonPhrase($statusCode);
                    break;
                case $e instanceof ErrException:
                    $statusCode = $e->getStatus();
                    $msg        = $e->getFriendlyMessage();
                    break;
                default:
                    $msg        = $e->getMessage();
                    $statusCode = $e->getCode() ? (string)$e->getCode() : '0';
                    break;
            }

            /** 获取当前路由相关信息 */
            if (empty(request()->route())) {
                $statusCode = $statusCode ?: Status::CODE_PARAMS_ERROR;
                $return     = [
                    'code'       => $statusCode,
                    'msg'        => $msg,
                    'request_id' => globalVariable()->getVariable('request_id'),
                ];
                return response()->json($return, $statusCode);
            }
            $actions = request()->route()->getAction() ?? [];
            //判断当前路由是否为web中间件
            if (!in_array('web', $actions['middleware'] ?? [])) {
                $return = [
                    'code'       => $statusCode,
                    'msg'        => $msg,
                    'request_id' => globalVariable()->getVariable('request_id'),
                ];
                return response()->json($return, $statusCode);
            } else {
                return response()->view('errors.error', ['errCode' => $statusCode, 'msg' => $msg, 'request_id' => tool()->value()->getVariable('request_id')]);
            }
        });
    }
}
