<?php

namespace gong\helper\traits;

trait Instance
{
    /** 实例 */
    public static function instance()
    {
        return new static(...func_get_args());
    }
}