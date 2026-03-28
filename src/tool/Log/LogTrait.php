<?php

namespace gong\tool\Log;

trait LogTrait
{
    protected static function infoLog(string $message, ?\Throwable $e = null, string $logCatalogue = '')
    {
        new static($message, 'info', $e, $logCatalogue);
    }

    protected static function errorLog(string $message, ?\Throwable $e = null, string $logCatalogue = '')
    {
        new static($message, 'error', $e, $logCatalogue);
    }

    protected static function debugLog(string $message, ?\Throwable $e = null, string $logCatalogue = '')
    {
        new static($message, 'debug', $e, $logCatalogue);
    }

    protected static function warningLog(string $message, ?\Throwable $e = null, string $logCatalogue = '')
    {
        new static($message, 'warning', $e, $logCatalogue);
    }
}