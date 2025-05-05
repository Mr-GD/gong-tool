<?php

namespace gong\tool\Log;

class Log
{
    public static $logPath;

    public static function init()
    {
        $logPath = env('LOGGER_PATH', '') ?: (self::$logPath ?: variable()->get('LOGGER_PATH'));
        if (!is_dir($logPath)) {
            @mkdir($logPath, 0777, true);
        }
        $logPath   .= date('Y-m-d') . '.log';
        $logHandle = new CLogFileHandler($logPath);
        Logger::Init($logHandle);
    }

    public static function info($msg)
    {
        self::init();
        Logger::INFO($msg);
    }

    public static function debug($msg)
    {
        self::init();
        Logger::DEBUG($msg);
    }

    public static function warning($msg)
    {
        self::init();
        Logger::WARN($msg);
    }

    public static function error($msg)
    {
        self::init();
        Logger::ERROR($msg);
    }
}