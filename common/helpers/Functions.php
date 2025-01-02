<?php

/**
 * 设置日志文件路径
 * @params string $route
 */

use common\Tool\Framework\Loading;

if (!function_exists('frameworkLoading')) {
    function frameworkLoading()
    {
        return Loading::instance()->analysisApplications()->execute();
    }
}
