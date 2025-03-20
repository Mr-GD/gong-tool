<?php

namespace common\Tool;

use gong\tool\base\abs\SingleCase;

class Redis extends SingleCase
{
    public static $instance;

    /** @var \Redis */
    public \Redis $redis;

    public function initialise()
    {
        $this->redis = new \Redis();
        $this->redis->connect(
            env('REDIS_HOST', '127.0.0.1'),
            env('REDIS_PORT', 6379)
        );
         $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select(env('REDIS_DB', 0));
    }
}
