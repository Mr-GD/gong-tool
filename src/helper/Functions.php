<?php

use gong\helper\GlobalVariable;
use JetBrains\PhpStorm\NoReturn;
use Ledc\Snowflake\Snowflake;

/**
 * 判断是否为二维数组
 */
if (!function_exists('isManyArray')) {
    function isManyArray($array)
    {
        // 首先检查是否是数组
        if (!is_array($array)) {
            return false;
        }

        // 遍历数组的每个元素
        foreach ($array as $element) {
            // 检查每个元素是否是数组
            if (!is_array($element)) {
                return false; // 如果有任何元素不是数组，则返回 false
            }
        }

        return true; // 所有元素都是数组，返回 true
    }
}

/**
 * 返回树形结构
 * @paramarray$data数据源
 * @paramstring$relationPk上下级关联字段
 * @paramstring$parent_key主键
 * @paramstring$child_key保存的子级键
 */
if (!function_exists('treeStructure')) {
    function treeStructure($data, $relationPk = 'pid', $parentKey = 'id', $childKey = 'children')
    {
        $return = [];
        $data   = array_column($data, null, $parentKey);
        foreach ($data as $item) {
            if (isset($data[$item[$relationPk]])) {
                $data[$item[$relationPk]][$childKey][] =& $data[$item[$parentKey]];
            } else {
                $return[] =& $data[$item[$parentKey]];
            }
        }
        return $return;
    }
}

/**
 * 导出csv
 * @param array $title 表头 [ 'phone' => '手机号' ]
 * @param array $data 导出数据
 * @param string $fileName
 */
if (!function_exists('exportCsv')) {
    #[NoReturn] function exportCsv(array $title, array $data, string $fileName = '导出csv文件')
    {
        ob_start();
        // 文件名，这里都要将utf-8编码转为gbk，要不可能出现乱码现象
        $fileName = date('Y-m-d') . iconv("UTF-8", "gbk", $fileName) . '.csv';

        // 头信息设置
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $fileName);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        $fp = fopen('php://output', 'a');

        $title = array_map(function ($v) {
            return iconv("UTF-8", "gbk", $v);
        }, $title);

        fputcsv($fp, $title);

        if (count($data) == count($data, 1)) {
            $data = [$data];
        }

        foreach ($data as $dv) {
            $temp = [];
            foreach ($dv as $vv) {
                $temp[] = iconv("UTF-8", "gbk", "{$vv}\t");
            }
            fputcsv($fp, $temp);
        }
        ob_flush();
        flush();  //刷新buffer
        exit;
    }
}

/**
 * ENV
 */
if (!function_exists('env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $key, mixed $default = null)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        $env = array_merge($_ENV ?? [], getenv());

        return $env[$key] ?? $default;
    }
}

/**
 * 全局变量类
 */
if (!function_exists('variable')) {
    function variable()
    {
        return GlobalVariable::instance();
    }
}

/**
 * 命令行输出
 */
if (!function_exists('consoleLine')) {
    function consoleLine($message)
    {
        if (!is_string($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }

        $date      = date('Y-m-d H:i:s.');
        $microtime = strval(round(microtime(true), 4));
        $microtime = explode('.', $microtime);
        $microtime = is_array($microtime) && count($microtime) == 2 ? $microtime[1] : '0';
        $microtime = str_pad($microtime, 4, '0');

        echo '[' . $date . $microtime . '] ' . $message . PHP_EOL;
    }
}

/**
 * 生成雪花ID
 * @param int $datacenterId 机房ID
 * @param int $workerId 机器ID
 */
if (!function_exists('generateSnowflakeId')) {
    function generateSnowflakeId(int $datacenterId = 0, int $workerId = 0)
    {
        $return[] = \gong\constant\Snowflake\Datacenter::LABELS[$datacenterId] ?? '';
        $return[] = snowflakeId($datacenterId, $workerId);
        $return   = array_filter($return);
        return implode('_', $return);
    }
}

/**
 * 生成雪花ID
 */
if (!function_exists('snowflakeId')) {
    function snowflakeId(int $datacenterId = 0, int $workerId = 0)
    {
        return (new Snowflake($datacenterId, $workerId))->id();
    }
}

/**
 * 生成PDF文件
 * @param string|array $fileAddress 文件地址 可以是远程url
 * @param string $saveAddress 保存地址
 * https://github.com/KnpLabs/KnpSnappyBundle
 */
if (!function_exists('generatePDF')) {
    function generatePDF($fileAddress, string $saveAddress)
    {
        $pdf = new Knp\Snappy\Pdf();
        return $pdf->generate($fileAddress, $saveAddress);
    }
}

/**
 * 生成图片文件
 * @param string $fileAddress 文件地址 可以是远程url
 * @param string $saveAddress 保存地址
 * https://github.com/KnpLabs/KnpSnappyBundle
 */
if (!function_exists('generateImage')) {
    function generateImage($fileAddress, string $saveAddress)
    {
        $image = new Knp\Snappy\Image();
        return $image->generate($fileAddress, $saveAddress);
    }
}

/**
 *  获取毫秒级时间戳
 */
if (!function_exists('millisecond')) {
    function millisecond()
    {
        // 获取当前时间戳，精确到微秒
        $microtime = microtime(true);
        // 获取毫秒部分（取微秒的前3位数字）
        $milliseconds = round(($microtime - floor($microtime)) * 1000);
        return sprintf('%s.%s', floor($microtime), $milliseconds);
    }
}

/**
 * 观察者单例模式
 */
if (!function_exists('listen')) {
    function listen()
    {
        return \gong\tool\Observer\ActionSingleCase::singleCase();
    }
}

/**
 * 获取IP
 */
if (!function_exists('getIp')) {
    function getIp()
    {
        // 检查可信代理传递的 X-Forwarded-For
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip  = trim($ips[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        // 检查其他可信头（需确保代理可信）
        $headers = ['HTTP_CLIENT_IP', 'HTTP_X_REAL_IP'];
        foreach ($headers as $header) {
            if (isset($_SERVER[$header]) && filter_var($_SERVER[$header], FILTER_VALIDATE_IP)) {
                return $_SERVER[$header];
            }
        }

        // 最终回退到 REMOTE_ADDR
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
}

if (!function_exists('renderYield')) {
    function renderYield($data, callable $callback = null)
    {
        foreach ($data as $item) {
            if (is_callable($callback)) {
                $item = $callback($item);
            }
            yield $item;
        }
    }
}