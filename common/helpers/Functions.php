<?php

/**
 * 设置日志文件路径
 * @params string $route
 */

use common\helpers\Auth;

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
 * 原生Redis实例
 */
if (!function_exists('tool')) {
    function tool()
    {
        return \common\helpers\Tool::instance();
    }
}
