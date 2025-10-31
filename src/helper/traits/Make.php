<?php

namespace gong\helper\traits;

trait Make
{
    /**
     * 实例
     * @return static
     */
    public static function make()
    {
        return new static(...func_get_args());
    }
}