<?php

namespace gong\helper\traits;

trait SingleCase
{
    public static ?self $instance = null;

    /**
     *
     * @return static
     * @date 2025/3/20 09:36
     */
    public static function instance()
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }

        static::$instance = new static();
        return static::$instance;
    }
}