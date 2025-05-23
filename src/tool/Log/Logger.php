<?php

namespace gong\tool\Log;

use gong\constant\Log\Level;

class Logger
{

    private $handler = null;
    private $level = 15;

    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function Init($handler = null, $level = 15)
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
            self::$instance->__setHandle($handler);
            self::$instance->__setLevel($level);
        }
        return self::$instance;
    }


    private function __setHandle($handler)
    {
        $this->handler = $handler;
    }

    private function __setLevel($level)
    {
        $this->level = $level;
    }

    public static function DEBUG($msg)
    {
        self::$instance->write(1, $msg);
    }

    public static function WARN($msg)
    {
        self::$instance->write(4, $msg);
    }

    public static function ERROR($msg)
    {
        $debugInfo = debug_backtrace();
        $stack     = "[";
        foreach ($debugInfo as $val) {
            if (array_key_exists("file", $val)) {
                $stack .= ",file:" . $val["file"];
            }
            if (array_key_exists("line", $val)) {
                $stack .= ",line:" . $val["line"];
            }
            if (array_key_exists("function", $val)) {
                $stack .= ",function:" . $val["function"];
            }
        }
        $stack .= "]";
        self::$instance->write(8, $stack . $msg);
    }

    public static function INFO($msg)
    {
        self::$instance->write(2, $msg);
    }

    private function getLevelStr($level)
    {
        return Level::LABELS[$level] ?? Level::INFO;
    }

    protected function write($level, $msg)
    {
        if (($level & $this->level) == $level) {
            $msg = is_string($msg) ? $msg : json_encode($msg, JSON_UNESCAPED_UNICODE);
            $msg = '[' . date('Y-m-d H:i:s') . '][' . $this->getLevelStr($level) . '] ' . $msg . "\n";
            $this->handler->write($msg);
        }
    }

}