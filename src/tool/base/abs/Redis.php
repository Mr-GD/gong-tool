<?php

namespace gong\tool\base\abs;

use gong\helper\traits\Instance;
use gong\helper\traits\Params;

abstract class Redis implements \gong\tool\base\api\Redis
{
    use Params,Instance;
    public string $redisKey = '';

    public function __construct()
    {
        $this->redisKey = $this->redisKey();
    }
}