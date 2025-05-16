<?php

namespace gong\helper\traits;

trait Instance
{
    /**
     * 实例
     * @return static
     */
    public static function instance()
    {
        return new static(...func_get_args());
    }
}