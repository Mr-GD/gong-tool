<?php

namespace gong\tool\base\abs;

use \gong\tool\base\api\SingleCase as SingleCaseApi;

abstract class SingleCase implements SingleCaseApi
{
    public static $instance;

    public function __construct()
    {
        $this->initialise();
    }

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
