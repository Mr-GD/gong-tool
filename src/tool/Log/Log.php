<?php

namespace gong\tool\Log;

use gong\helper\traits\Make;

/**
 * @method $this setLogCatalogue(string $logCatalogue) 设置日志目录
 */
abstract class Log
{
    use Make, LogTrait;

    protected string $dir;
    protected string $millFormatDate;

    /**
     * @var bool 是否异步记录
     */
    protected bool $isAsync = false;

    public function __construct(
        protected string $message,
        protected string $logType,
        /**
         * @var string info、warning、error、debug
         */
        protected ?\Throwable $e = null,
        /**
         * @var string 日志文件夹
         */
        protected string $logCatalogue = ''
    )
    {
        $this->option();
    }

    /**
     * @return mixed
     * @doc 异步记录日志
     */
    abstract public function asyncRecord();

    protected function option()
    {
        $this->millFormatDate = millisecondFormatDate();
        $runtimeLogDir        = rtrim(variable()->get('runtime_log_dir', '/runtime/logger'), '/') . '/';
        $logCatalogue         = $this->logCatalogue ?: str_replace('\\', '.', get_called_class());
        $this->dir            = sprintf('%s%s/%s',
            $runtimeLogDir,
            $logCatalogue,
            date('Y/m/d')
        );

        /** 异步记录 */
        if ($this->isAsync) {
            $this->asyncRecord();
            return;
        }

        /** 同步记录 */
        $this->record();
    }

    public function record()
    {
        if (!is_dir($this->dir) && !mkdir($this->dir, 0755, true) && !is_dir($this->dir)) {
            return;
        }

        $maxNum = -1;
        $files  = glob($this->dir . '/log-*.log');
        if ($files) {
            foreach ($files as $file) {
                if (preg_match('#log-(\d+)\.log$#i', $file, $m)) {
                    $num = (int)$m[1];
                    if ($num > $maxNum) $maxNum = $num;
                }
            }
        }

        $fileCount = max($maxNum, 0);
        $maxSize   = variable()->get('runtime_max_file_size', 52428800); //50M
        $checkFile = $this->dir . sprintf('/log-%s.log', $fileCount);

        if (file_exists($checkFile)) {
            $fileSize = filesize($checkFile) ?: 0;
            if ($fileSize >= $maxSize) {
                $fileCount++;
            }
        }

        $fileDir = $this->dir . sprintf('/log-%s.log', $fileCount);
        $logMsg  = sprintf(' [%s] ', $this->logType) . trim($this->message);
        if ($this->e !== null) {
            $logMsg .= '| Exception: ' . $this->e->getMessage() . PHP_EOL . $this->e->getTraceAsString();
        }

        $write  = sprintf('[%s] %s%s', $this->millFormatDate, $logMsg, PHP_EOL);
        $handle = fopen($fileDir, 'ab');

        if (!$handle) {
            return;
        }

        try {
            flock($handle, LOCK_EX);
            fwrite($handle, $write);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }
    }
}