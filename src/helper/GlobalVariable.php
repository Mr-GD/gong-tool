<?php

namespace gong\helper;

use gong\helper\traits\SingleCase;

class GlobalVariable
{
    use SingleCase;

    /** @var array 通用变量 */
    public array $variable = [
        'LOGGER_PATH' => './runtime/logs/', //日志保存默认地址
    ];

    /**
     * 保存变量
     * @param $name
     * @param $value
     * @date 2024/12/8 14:27
     */
    public function set($name, $value)
    {
        if (is_callable($value) && (is_object($value) || is_array($value))) {
            $saveValue = $value($this);
        } else {
            $saveValue = $value;
        }

        $this->variable[strtoupper($name)] = $saveValue;
    }

    public function get($name, $default = '')
    {
        return $this->variable[strtoupper($name)] ?? $default;
    }
}
