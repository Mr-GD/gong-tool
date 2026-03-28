<?php

namespace gong\helper\traits;

use gong\tool\Log\Log;

trait UsageLogs
{

    /**
     * @param $message
     * @param string $level info、error、debug、warning
     * @param \Throwable|null $e
     */
    protected function log($message, string $level = 'info', ?\Throwable $e = null)
    {
        $logCatalogue = $this->getLogCatalogue();
        /** @var Log $logger */
        $logger = variable()->get('LOGGER');
        if (empty($logger)) {
            throw new \Exception('LOGGER is not exist');
        }
        call_user_func([$logger, $level], $message, $e, $logCatalogue);
    }
}