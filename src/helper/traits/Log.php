<?php

namespace gong\helper\traits;

trait Log
{
    protected function log($message)
    {
        $runtimeLogDir = variable()->get('runtime_log_dir', '/runtime/logs/');
        $logCatalogue  = method_exists($this, 'getLogCatalogue') ? $this->getLogCatalogue() : str_replace('\\', '.', get_called_class());
        $dir           = sprintf('%s%s/%s/%s/', $runtimeLogDir, $logCatalogue, date('Y'), date('m'));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileDir = $dir . sprintf('%s.log', date('d'));
        $message .= PHP_EOL;
        file_put_contents($fileDir, $message, FILE_APPEND);
    }
}