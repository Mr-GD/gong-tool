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

    /**
     * @var string 请求ID
     */
    protected string $requestId;

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
        $this->ip             = getIp();
        $this->requestId      = variable()->get('request_id', '-');
    }

    public function record()
    {
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
            if (!is_dir($this->dir)) {
                return;
            }
        }

        // ──── 4 槽持柄缓存 ────
        static $cache = []; // ['dir' => ['file','size','handle','lastWrite']]

        $slot = &$cache[$this->dir];

        $hit = isset($slot['file'])
            && $slot['size'] < $this->maxFileSize
            && is_resource($slot['handle']);

        if (!$hit) {
            if (isset($slot['handle']) && is_resource($slot['handle'])) {
                fclose($slot['handle']);
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
            $fileSize  = 0;

            if (file_exists($checkFile)) {
                $fileSize = filesize($checkFile) ?: 0;
                if ($fileSize >= $this->maxFileSize) {
                    $fileCount++;
                    $fileSize = 0;
                }
            }

            $fileDir = $this->dir . sprintf('/log-%s.log', $fileCount);

            $handle = fopen($fileDir, 'ab');
            if (!$handle) {
                return;
            }

            $slot = [
                'file'   => $fileDir,
                'size'   => $fileSize,
                'handle' => $handle,
            ];

            // 超过 4 槽：关最旧的
            if (count($cache) > 4) {
                $ordered = array_keys($cache);
                $keep    = array_slice($ordered, -4, 4);
                foreach ($cache as $k => $v) {
                    if (!in_array($k, $keep, true)) {
                        is_resource($v['handle']) && fclose($v['handle']);
                        unset($cache[$k]);
                    }
                }
            }
        }

        $logMsg = sprintf('[%s]', $this->logType) . trim($this->message);
        if ($this->e !== null) {
            $logMsg .= sprintf(' | [%s] Exception: ', $this->_getCode())
                . $this->_getMessage()
                . PHP_EOL
                . $this->e->getTraceAsString();
        }

        $write = sprintf('[%s][%s][%s] %s%s',
            $this->millFormatDate,
            $this->ip,
            $this->requestId,
            $logMsg,
            PHP_EOL);

        if ($this->isAsync) {
            $written = fwrite($slot['handle'], $write);
        } else {
            flock($slot['handle'], LOCK_EX);
            $written = fwrite($slot['handle'], $write);
            flock($slot['handle'], LOCK_UN);
        }

        $slot['size']      += ($written !== false ? $written : 0);
        $slot['lastWrite'] = time();

        // ──── 惰性回收：关闭超过 10 分钟没写入的句柄 ────
        $now    = time();
        $expire = $now - 600; // 10 分钟
        foreach ($cache as $k => $v) {
            if ($v['lastWrite'] < $expire) {
                is_resource($v['handle']) && fclose($v['handle']);
                unset($cache[$k]);
            }
        }
    }

    private function _getCode()
    {
        try {
            return method_exists($this->e, 'getCode') ? $this->e->getCode() : '-';
        } catch (\Throwable $e) {
            return '-';
        }
    }

    private function _getMessage()
    {
        try {
            return method_exists($this->e, 'getMessage') ? $this->e->getMessage() : '';
        } catch (\Throwable $e) {
            return '';
        }
    }
}