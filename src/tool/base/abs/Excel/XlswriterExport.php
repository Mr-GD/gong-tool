<?php

namespace gong\tool\base\abs\Excel;

use gong\helper\traits\Instance;
use gong\helper\traits\Params;
use Vtiful\Kernel\Excel;

/**
 * Excel文件导出基类
 * @method $this setWhetherPage(bool $whetherPage) 设置是否分页导出
 * @method $this setDownload(bool $isDownload) 是否下载文件
 */
abstract class XlswriterExport
{
    use Params, Instance;

    protected $exportData = [];

    protected $title;

    protected $fileName;

    /** 表头 */
    abstract protected function setTitle(): array;

    /** 文件名 */
    abstract protected function setFileName(): string;

    /** 导出数据 */
    abstract protected function setExportData(): array;

    abstract protected function beforeExport();

    /**
     * @var Excel
     */
    public $excel;

    /**
     * @var array
     */
    public $savePath;
    public $lines = 1;
    protected $download = true;

    /**
     * @var bool 是否为分页导出
     */
    protected $whetherPage = false;

    public function __construct(string $savePath = '')
    {
        $this->initialise($savePath);
    }

    public function export()
    {
        ob_start();
        /** 分页导出 */
        if ($this->whetherPage) {
            $this->queryDataBatches();
        } else {
            $this->exportData = $this->setExportData();
            $this->render();
        }

        return $this->execution();
    }

    /**
     * 分页查询
     */
    public function queryDataBatches()
    {
        while (true) {
            $this->exportData = $this->setExportData();
            if (empty($this->exportData)) {
                break;
            }
            $this->render();
        }
        $this->exportData = [];
    }

    public function render()
    {
        $this->beforeExport();
        $methodExists = method_exists($this, 'customProcessing');
        $fields       = array_keys($this->title);
        foreach (renderYield($this->exportData) as $data) {
            $column = 0;
            foreach ($fields as $field) {
                $value = $methodExists ? (string)$this->customProcessing($data, $field) : $this->getExportData($data, $field);
                $this->excel->insertText($this->lines, $column, (string)$value);
                $column++;
            }
            $this->lines++;
        }
    }

    public function initialise($savePath)
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->savePath = $this->setDir($savePath);
        $this->title    = $this->setTitle();
        $this->fileName = $this->setFileName();
        $excel          = new Excel($this->config($this->savePath));
        $this->excel    = $excel->fileName($this->fileName . '.xlsx')
                                ->header(array_values($this->title))
        ;
        if ($this->whetherPage) {
            $this->excel->constMemory($this->fileName . '.xlsx');
        }
    }

    /**
     * 执行
     * @return string|void
     */
    public function execution()
    {
        /** 不是下载 */
        if (!$this->download) {
            return $this->excel->output(); // 返回文件地址 本地文件地址 全路径 例：/www/wwwroot/flash_sign_cloud/console/runtime/SaveLocalFile/xxx.xlsx
        }

        $this->setHeader();
        $filePath = $this->excel->output();

        // 注册关闭函数，在脚本结束时删除文件
        register_shutdown_function(function () use ($filePath) {
            ob_flush();
            flush();  //刷新buffer
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });
        // 输出文件内容
        readfile($filePath);
        exit;
    }

    public function config($savePath)
    {
        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755, true);
        }

        $return = [
            'path' => $this->savePath,
        ];

        if ($this->whetherPage) {
            $return['memory_mode'] = 'serialize'; // 或 'temp';
        }
        return $return;
    }

    public function setDir(string $savePath = '')
    {
        $dir = variable()->get('runtime_dir', '/');
        if ($savePath) {
            $dir .= $savePath;
        } else {
            $classDir = explode('\\', get_called_class());
            $classDir = array_splice($classDir, -2, 2);
            $dir      .= '/Xlswriter/' . implode('/', $classDir) . '/';
        }

        return $dir;
    }

    public function setHeader()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment;filename="%s.xlsx"', $this->fileName));
        header('Cache-Control: max-age=0');
        header('Pragma: public');
    }

    protected function getExportData($data, $field)
    {
        $method    = '';
        $tempField = explode('_', $field);
        foreach ($tempField as $temp) {
            $method .= ucfirst($temp);
        }

        if (!method_exists($this, $method)) {
            return $this->noMethodExists($data, $field, $method);
        }

        $return = call_user_func_array([$this, $method], [$data]);
        return (string)$return;
    }

    protected function noMethodExists($data, $field, $method)
    {
        if (isset($data[$field])) {
            return (string)$data[$field] ?? '';
        }

        $formatMethod = 'format' . ucfirst($method);
        if (method_exists($this, $formatMethod)) {
            $return = call_user_func_array([$this, $formatMethod], [$data, $field]);
            return (string)$return;
        }

        return '';
    }
}