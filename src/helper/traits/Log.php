<?php

namespace gong\helper\traits;

trait Log
{
    protected function log($message)
    {
        $runtimeLogDir = variable()->get('runtime_log_dir', '/runtime/logs/');
        $logCatalogue  = method_exists($this, 'getLogCatalogue') ? $this->getLogCatalogue() : str_replace('\\', '.', get_called_class());
        $dir           = sprintf('%s%s/%s/%s/%s', $runtimeLogDir, $logCatalogue, date('Y'), date('m'), date('d'));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $fileCount = countFilesInDir($dir);
        $fileDir   = $dir . sprintf('/%s.log', $fileCount);
        $fileSize  = getFileSize($fileDir);

        if ($fileSize > variable()->get('runtime_max_file_size', 50000)) {
            $fileDir = $dir . sprintf('/%s.log', ++$fileCount);
        }

        $write = sprintf('[%s]%s%s', millisecondFormatDate(), $message, PHP_EOL);
        file_put_contents($fileDir, $write, FILE_APPEND);
    }
}