<?php

namespace gong\tool\base\api;

interface SingleCase
{
    public static function instance();

    public function initialise();
}