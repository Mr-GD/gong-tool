<?php

/**
 * 设置日志文件路径
 * @params string $route
 */

use common\helpers\Auth;
use common\Tool\Framework\Loading;

if (!function_exists('frameworkLoading')) {
    function frameworkLoading()
    {
        return Loading::instance()->analysisApplications()->execute();
    }
}

/** 遍历递归引入目录下php文件 */
if (!function_exists('requireAllPhpFiles')) {
    function requireAllPhpFiles($dir)
    {
        // 获取目录中的所有文件和目录
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            // 确保是文件并且是PHP文件
            if ($file->isFile() && $file->getExtension() === 'php') {
                // 引入文件
                require_once $file->getPathname();
            }
        }


    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return new Auth();
    }
}

/**
 * 遍历获取目录文件
 */
if (!function_exists('recursiveGlob')) {
    function recursiveGlob($pattern)
    {
        $allFiles = [];
        $files    = glob($pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                $allFiles[] = $file;
            }

            if (is_dir($file)) {
                $subFiles = recursiveGlob($file . '/*');
                $allFiles = array_merge($allFiles, $subFiles);
            }
        }
        return $allFiles;
    }
}

/**
 * 原生Redis实例
 */
if (!function_exists('redis')) {
    function redis()
    {
        return \common\Tool\Redis::instance();
    }
}
