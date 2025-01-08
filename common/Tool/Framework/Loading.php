<?php

namespace common\Tool\Framework;

use App\Http\Middleware\CORS;
use gong\helper\Instance;
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
        return $this;
    }

    public function execute()
    {
        $this->setLogDir();
        $this->setRequestId();
        $this->setRuntimeDir();
        return true;
    }

    /**
     * 设置日志文件路径
     * @author 龚德铭
     * @date 2024/12/24 10:11
     */
    public function setLogDir()
    {
        /** 设置日志文件路径 */
        $logDir = str_replace('/public', '', getcwd()) . sprintf('/runtime/logs/%s/%s/%s/', $this->model, date('Y'), date('m'));
        globalVariable()->setVariable('LOGGER_PATH', $logDir);
    }

    /**
     * 设置当前请求操作唯一ID
     * @author 龚德铭
     * @date 2024/12/24 10:11
     */
    public function setRequestId()
    {
        $flip         = array_flip(\gong\constant\Snowflake\Datacenter::LABELS);
        $datacenterId = $flip[$this->model] ?? 0;
        $snowflakeId  = generateSnowflakeId($datacenterId);
        globalVariable()->setVariable('REQUEST_ID', $snowflakeId);
    }

    /**
     * 设置runtime位置
     * @author 龚德铭
     * @date 2025/1/1 23:55
     */
    public function setRuntimeDir()
    {
        /** 设置日志文件路径 */
        $logDir = str_replace('/public', '', getcwd()) . '/runtime/';
        globalVariable()->setVariable('runtime_dir', $logDir);
    }

    public function loadMiddleware(Middleware $middleware)
    {
        /** 跨域 */
        $middleware->append(CORS::class);
//        switch ($this->model) {
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
}
