<?php

namespace gong\helper\traits;

/**
 * @method array defaultVariables()
 */
trait HasVariables
{
    protected $variables = [];

    /**
     * 获取所有变量.
     *
     * @return array
     */
    public function variables()
    {
        if (!method_exists($this, 'defaultVariables')) {
            return $this->variables;
        }

        return array_merge($this->defaultVariables(), $this->variables);
    }

    /**
     * 设置变量.
     *
     * @param  array  $variables
     * @return $this
     */
    public function addVariables(array $variables = [])
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * 检查是否存在变量
     * @return bool
     */
    public function hasVariable($key)
    {
        return key_exists($key, $this->variables());
    }

    /**
     * 获取变量
     */
    public function getVariable($key)
    {
        return $this->variables()[$key];
    }

    /**
     * 设置变量
     */
    public function setVariable($key, $value)
    {
        $this->addVariables([$key => $value]);

        return $this;
    }

    /**
     * 获取变量, 或设置一个默认变量
     */
    public function rememberVariable($key, \Closure $callback)
    {
        if ($this->hasVariable($key)) {
            return $this->getVariable($key);
        }

        $value = $callback();

        $this->setVariable($key, $value);

        return $value;
    }
}
