<?php

namespace gong\tool;

use gong\helper\traits\Data;
use gong\helper\traits\SingleCase;

/**
 * 任务类
 * @method string getTaskId() 获取任务ID
 */
class Task
{
    use SingleCase, Data;

    protected $taskId;

    public function __construct()
    {
        $this->taskId = generateSnowflakeId();
    }
}