<?php

namespace App\Exceptions;

use common\Exception\Code;
use Exception;
use Jiannei\Response\Laravel\Response;

class Unusual extends Exception
{

    public function __construct($code = 200, $message = "", $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 转换异常为 HTTP 响应
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
//        return response()->json(['code' => $this->getCode(), 'msg' => $this->getMessage()]);
        return (new Response())->fail($this->getMessage(), $this->getCode(), [Code::STATUS_TEXTS[$this->getCode()] ?? '']);
    }
}

