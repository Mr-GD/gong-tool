<?php

namespace gong\tool\base\abs;

use gong\helper\traits\Params;
use gong\helper\traits\SingleCase;

abstract class Redis implements \gong\tool\base\api\Redis
{
    use Params, SingleCase;

    public string $key = '';

    public function __construct(...$args)
    {
        $this->key = $this->redisKey(...$args);
    }
}