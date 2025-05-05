<?php

namespace gong\helper\traits;

/**
 * 给使用类添加 getter 和 setter 方法
 * @example
 * class Obj {
 *     use Data;
 *     private $name;
 * }
 *
 * $obj = new Obj();
 *
 * # 设置属性:
 * $obj->setName('name');
 * # 或
 * $obj->name('name');
 *
 * # 获取属性
 * $obj->getName();
 * # 或
 * $obj->name();
 *
 */
trait Data
{
    public function __call($name, $arguments)
    {
        $prefixes = ['get', 'set'];
        foreach ($prefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                $name = lcfirst(substr($name, strlen($prefix)));
                break;
            }
        }

        if (property_exists($this, $name)) {
            if (empty($arguments)) {
                return $this->{$name};
            } else {
                $this->{$name} = $arguments[0];
                return $this;
            }
        }

        return $this;
    }
}