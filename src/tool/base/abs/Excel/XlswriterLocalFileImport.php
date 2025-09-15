<?php

namespace gong\tool\base\abs\Excel;

use gong\helper\traits\HasVariables;
use gong\helper\traits\Instance;
use gong\helper\traits\Log;
use gong\helper\traits\Params;
use gong\tool\File\SaveLocally;
use Vtiful\Kernel\Excel;

/**
 * @method $this setRemoteFile(string $remoteFile) 设置远程文件地址
 * @method array getImportData()
 */
abstract class XlswriterLocalFileImport
{
    use Instance, Params, HasVariables, Log;

    protected $importData = [];

    /**
     * @var string 远程文件地址
     */
    protected $remoteFile;

    /**
     * @var string 保存的地址文件地址
     */
    protected $localFile;

    /**
     * @var Excel
     */
    protected $excel;

    protected $currentLines = 0;

    /**
     * @var array 表头
     */
    protected $title;

    /**
     * @var int[]|string[]
     */
    protected $keys;

    /**
     * @var int 表头长度
     */
    protected $titleLength;

    protected $sheetName = [];

    /**
     * 格式化导入数据
     * @param $row
     * @return array
     *
     * 绑定key->value
     * array_combine($this->keys, $row);
     */
    protected abstract function formatData($row): array;

    protected abstract function title();

    public function execute()
    {
        try {
            $this->localFile = SaveLocally::instance($this->remoteFile)->execute();
            $pathInfo        = pathinfo($this->localFile);
            $this->excel     = new Excel(['path' => $pathInfo['dirname']]);
            $this->excel->openFile($pathInfo['basename']);
            $this->before();
            $this->excel->openSheet();
            while (($row = $this->excel->nextRow()) !== null) {
                $this->currentLines++;
                $data = $this->formatData($row);
                if (empty($data)) {
                    continue;
                }

                $this->importData[] = $data;
            }
        } catch (\Throwable $e) {
            $this->log(sprintf('【%s】导入失败 Error:%s', get_called_class(), $e->getMessage()));
            throw $e;
        }

        return $this;
    }

    public function before()
    {
        $this->title       = $this->title();
        $this->keys        = array_keys($this->title);
        $this->titleLength = count($this->title);
        /** sheetList()方法代码里没找到，但官方文档上有 */
        $this->sheetName = $this->excel->sheetList();
        if (empty($this->sheetName)) {
            throw new \Exception('没有找到sheet');
        }
    }

    public function __destruct()
    {
        if ($this->localFile) {
            @unlink($this->localFile);
        }

        /** 清除variables变量中临时保存的数据 */
        $this->variables = [];
    }
}