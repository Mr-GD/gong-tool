<?php

namespace gong\constant\Snowflake;

class Datacenter
{
    const ADMIN   = 1;
    const API     = 2;
    const PC      = 3;
    const H5      = 4;
    const XCX     = 5;
    const APP     = 6;
    const ARTISAN = 7;
    const CLI     = 8;

    const LABELS = [
        self::ADMIN   => 'admin',
        self::API     => 'api',
        self::PC      => 'pc',
        self::H5      => 'h5',
        self::XCX     => 'xcx',
        self::APP     => 'app',
        self::ARTISAN => 'artisan',
        self::CLI     => 'cli',
    ];
}