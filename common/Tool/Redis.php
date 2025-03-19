<?php

namespace common\Tool;

class Redis
{
    public static $instance;

    /** @var \Redis */
    public \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(
            env('REDIS_HOST', '127.0.0.1'),
            env('REDIS_PORT', 6379)
        );
        $this->redis->auth(env('REDIS_PASSWORD'));
        $this->redis->select(env('REDIS_DB', 0));
    }

    public static function instance()
    {
        if (self::$instance instanceof (new self)) {
            return self::$instance->redis;
        }

        self::$instance = new self;
        return self::$instance->redis;
    }
}
