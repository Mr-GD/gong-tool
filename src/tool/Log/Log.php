<?php

namespace gong\tool\Log;

use gong\helper\traits\Make;

/**
 * @method $this setLogCatalogue(string $logCatalogue) 设置日志目录
 * @method $this setLogType(string $logType) 设置日志类型
 */
abstract class Log
{
    use Make;

    protected string $dir;
    protected string $millFormatDate;

    /**
     * @var bool 是否异步记录
     */
    protected bool $isAsync = false;

    protected string $message;

    protected ?\Throwable $e;

    protected int $maxFileSize = 52428800;

    protected int $fileCount;

    protected ?string $ip;

    /**
     * @var string info、warning、error、debug
     */
    protected string $logType;

    public function __construct(
        /**
         * @var string 日志文件夹
         */
        protected string $logCatalogue = ''
    )
    {
        $this->option();
    }

    /**
     * @doc 异步记录日志
     */
    abstract protected function asyncRecord();

    public function info($message, ?\Throwable $e = null)
    {
        $this->logType = 'info';
        $this->execute($message, $e);
    }

    public function warning($message, ?\Throwable $e = null)
    {
        $this->logType = 'warning';
        $this->execute($message, $e);
    }

    public function error($message, ?\Throwable $e = null)
    {
        $this->logType = 'error';
        $this->execute($message, $e);
    }

    public function debug($message, ?\Throwable $e = null)
    {
        $this->logType = 'debug';
        $this->execute($message, $e);
    }

    protected function execute($message, ?\Throwable $e = null)
    {
        $this->message = is_string($message) ? $message : json_encode($message, JSON_UNESCAPED_UNICODE);
        $this->e       = $e;
        if ($this->isAsync) {
            $this->asyncRecord();
            return;
        }
        $this->record();
    }

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
        $this->maxFileSize    = variable()->get('runtime_max_file_size', 52428800); //50M
        $this->ip = getIp();
    }

    public function record()
    {
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
            if (!is_dir($this->dir)) {
                return;
            }
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
        $checkFile = $this->dir . sprintf('/log-%s.log', $fileCount);

        if (file_exists($checkFile)) {
            $fileSize = filesize($checkFile) ?: 0;
            if ($fileSize >= $this->maxFileSize) {
                $fileCount++;
            }
        }

        $fileDir = $this->dir . sprintf('/log-%s.log', $fileCount);
        $logMsg  = sprintf('[%s] ', $this->logType) . trim($this->message);
        if ($this->e !== null) {
            $logMsg .= sprintf(' | [%s] Exception: ', $this->e->getCode()) . $this->e->getMessage() . PHP_EOL . $this->e->getTraceAsString();
        }

        $write  = sprintf('[%s][%s][%s] %s%s',
            $this->millFormatDate,
            $this->ip,
            variable()->get('request_id', '-'),
            $logMsg,
            PHP_EOL);

        if ($this->isAsync) {
            file_put_contents($fileDir, $write, FILE_APPEND);
            return;
        }

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