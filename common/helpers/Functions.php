<?php

/**
 * 设置日志文件路径
 * @params string $route
 */

use common\Constant\Storage\Mode;
use common\Tool\Framework\Loading;

if (!function_exists('frameworkLoading')) {
    function frameworkLoading()
    {
        return Loading::instance()->analysisApplications()->execute();
    }
}

/**
 * 格式化存储文件地址
 * @params string $url
 * @params int $mode
 */
if (!function_exists('formatStorageFileUrl')) {
    function formatStorageFileUrl(string $url, int $mode = 1)
    {
        if (str_contains($url, 'http:') || str_contains($url, 'https:')) {
            return $url;
        }

        $return = '';
        switch ($mode) {
            case Mode::LOCAL:
                $return = $url;
                break;
            case Mode::KODBOX:
                $return = env('UPLOAD_FILE_KODBOX_API_URL') . $url;
                break;
        }

        return $return;
    }
}
