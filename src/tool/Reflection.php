<?php

namespace gong\tool;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * 反射类
 * https://github.com/Roave/BetterReflection
 */
class Reflection
{
    /**
     * @param string $classname
     * @return ReflectionClass
     */
    public static function make(string $classname)
    {
        return new BetterReflection()->reflector()->reflectClass($classname);
    }
}