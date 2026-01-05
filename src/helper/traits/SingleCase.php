<?php

namespace gong\helper\traits;

trait SingleCase
{
    protected static ?self $instance = null;

    /**
     * @param ...$args
     * @return static
     */
    public static function instance(...$args)
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }

        static::$instance = new static(...$args);
        return static::$instance;
    }

    /** 禁止克隆 */
    private function __clone() {}

    /** 禁止外部实例化 */
    private function __construct(...$args) {}
}