<?php

namespace gong\tool\base\api;

/** 工厂模式 */
interface Factory
{
    public static function create($type);
}