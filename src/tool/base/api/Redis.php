<?php

namespace gong\tool\base\api;

interface Redis
{
    public function redisKey(): string;
}