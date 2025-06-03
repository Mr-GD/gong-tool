<?php

namespace gong\tool\base\abs\Excel;

use gong\helper\traits\Params;
use gong\tool\base\api\Excel\Callback;
use gong\tool\base\api\Excel\Data;
use gong\tool\base\api\Excel\Paging;
use gong\tool\base\api\Excel\Title;
use gong\tool\base\api\Execute;
use Vtiful\Kernel\Excel;

/**
 * @method $this setFilename(string $filename) 设置文件名
 * @method $this setExportData(array $exportData) 设置导出数据
 * @method $this setLimit(int $limit) 设置每页数量
 */
abstract class Export implements Execute, Title, Data, Callback, Paging
{
    use \gong\helper\traits\Data, Params;

    public string $filename;

    /**
     * @var string 保存路径
     */
    public string $savePath;

    public Excel $excel;

    public int $lines = 1;

    /**
     * @var int 每页数量
     */
    public int $limit = 10000;

    /**
     * @var bool 是否分页导出
     */
    public bool $isPaging = false;

    public function __construct(string $savePath = '')
    {
        $excel          = new Excel($this->config($savePath));
        $this->excel    = $excel->header($this->title());
        $this->savePath = $savePath;
    }

    public function config($savePath)
    {
        return [
            'path' => empty($savePath) ? '/temp' : $savePath
        ];
    }

    public function execute()
    {
        $this->formatFilename();
        if (!$this->savePath) {
            $this->setHeader();
        }
        $this->excel->fileName($this->filename)
                    ->header(array_values($this->title()))
        ;

        if ($this->isPaging) {
            $this->exportByPage();
        } else {
            $this->handleData($this->data());
        }

        $this->callable($this);

        return $this->excel->output();
    }

    /**
     * 分页导出
     */
    public function exportByPage()
    {
        $page = 1;
        do {
            $datas = $this->paging($page, $this->limit);
            if (empty($datas)) {
                break;
            }

            $this->handleData($datas);

            ob_flush();
            flush();
            $page++;
        } while (true);
    }

    public function handleData($exportData)
    {
        foreach ($exportData as $datas) {
            $column = 0;
            foreach ($datas as $key => $value) {
                if (method_exists($this, $key)) {
                    $this->$key($column, $value);
                } else {
                    $this->excel->insertText($this->lines, $column, (string)$value);
                }
                $column++;
            }
            $this->lines++;
        }
    }

    public function setHeader()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment;filename="%s"', $this->filename));
        header('Cache-Control: max-age=0');
    }

    public function formatFilename()
    {
        $filename = $this->filename ?: '下载文件.xlsx';
        $explode  = explode('.', $filename);

        $this->filename = sprintf('%s.%s',
            $explode[0] ?? '',
            $explode[1] ?? 'xlsx'
        );
    }
}