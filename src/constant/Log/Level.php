<?php

namespace gong\constant\Log;

class Level
{
    const DEBUG = 1;
    const INFO  = 2;
    const WARN  = 4;
    const ERROR = 8;

    const LABELS = [
        self::DEBUG => 'debug',
        self::INFO  => 'info',
        self::WARN  => 'warn',
        self::ERROR => 'error',
    ];
}