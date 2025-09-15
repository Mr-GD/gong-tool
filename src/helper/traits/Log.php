<?php

namespace gong\helper\traits;

trait Log
{
    protected function log($message)
    {
        if (function_exists('logWrite')) {
            logWrite($message);
        }
    }
}