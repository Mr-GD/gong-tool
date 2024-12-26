<?php

namespace common\Tool\Framework;

use gong\helper\Instance;

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
        return true;
    }

    public function loadSessionStory()
    {
        session_start();
    }

    /**
     * 设置日志文件路径
     * @author 龚德铭
     * @date 2024/12/24 10:11
     */
    public function setLogDir()
    {
        /** 设置日志文件路径 */
        $logDir = str_replace('/public', '', getcwd()) . sprintf('/runtime/logs/%s/', $this->model);
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
}
