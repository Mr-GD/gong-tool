<?php

namespace gong\tool\base\api;

interface Redis
{
    /**
     * Redis实例
     * @return mixed
     */
    public function instantiation();
    public function redisKey(...$args): string;
}