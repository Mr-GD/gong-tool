<?php

namespace gong\tool\base\abs;

use gong\helper\traits\Instance;
use gong\helper\traits\Params;

abstract class Redis implements \gong\tool\base\api\Redis
{
    use Params, Instance;
    public string $key = '';

    public function __construct(...$args)
    {
        $this->key = $this->redisKey(...$args);
    }
}