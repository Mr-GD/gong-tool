<?php

namespace common\Tool\Framework;

use App\Http\Middleware\CORS;
use App\Http\Middleware\ParameterValidation;
use gong\constant\Snowflake\Datacenter;
use gong\helper\traits\Instance;
use Illuminate\Foundation\Configuration\Middleware;

class Loading
{
    use Instance;

    public string $model;

    public function analysisApplications()
    {
        $requestUri  = explode('/', $_SERVER['REQUEST_URI'] ?? '');
        $model       = collect($requestUri)->filter()->shift();
        $this->model = $model ?: 'artisan';
        /** 设置全局变量 */
        variable()->set('request_model', $this->model);
        return $this;
    }

    public function execute()
    {
        $this->setLogDir();
        $this->setRequestId();
        $this->setRuntimeDir();
        $this->setOperationTime();
    }

    /**
     * 设置日志文件路径
     * @date 2024/12/24 10:11
     */
    public function setLogDir()
    {
        /** 设置日志文件路径 */
        $logDir = str_replace('/public', '', getcwd()) . sprintf('/runtime/logs/%s/%s/%s/', $this->model, date('Y'), date('m'));
        variable()->set('logger_path', $logDir);
    }

    /**
     * 设置当前请求操作唯一ID
     * @date 2024/12/24 10:11
     */
    public function setRequestId()
    {
        $flip         = array_flip(Datacenter::LABELS);
        $datacenterId = $flip[$this->model] ?? 0;
        $snowflakeId  = generateSnowflakeId($datacenterId);
        variable()->set('request_id', $snowflakeId);
    }

    /**
     * 设置runtime位置
     * @date 2025/1/1 23:55
     */
    public function setRuntimeDir()
    {
        /** 设置日志文件路径 */
        $logDir = str_replace('/public', '', getcwd()) . '/runtime/';
        variable()->set('runtime_dir', $logDir);
    }

    public function loadMiddleware(Middleware $middleware)
    {
        /** 跨域 */
        $middleware->append(CORS::class)
                   ->append(ParameterValidation::class)
        ;
        $model = variable()->get('request_model');
//        switch ($model) {
//            case 'admin':
//                break;
//            case 'api':
//                break;
//            case 'artisan':
//                break;
//            case 'web':
//                break;
//            default:
//                break;
//        }

        return true;
    }

    /**
     * 设置操作时间
     * @date 2025/3/19 11:09
     */
    public function setOperationTime()
    {
        variable()->set('operation_time', time());
    }

}
