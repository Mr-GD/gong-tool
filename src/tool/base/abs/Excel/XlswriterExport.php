<?php /** @noinspection PhpExpressionResultUnusedInspection */

namespace gong\tool\base\abs\Excel;

use gong\helper\traits\Instance;
use gong\helper\traits\Params;
use Vtiful\Kernel\Excel;

/**
 * Excel文件导出基类
 * @method $this setWhetherPage(bool $whetherPage) 设置是否分页导出
 * @method $this setDownload(bool $isDownload) 是否下载文件
 *
 * https://xlswriter-docs.viest.me/zh-cn
 */
abstract class XlswriterExport
{
    use Params, Instance;

    protected $exportData = [];

    protected $title;

    protected $fileName;

    /**
     * @var Excel
     */
    protected $excel;

    /**
     * @var array
     */
    protected $savePath;
    protected $lines = 1;
    protected $download = true;

    protected $path = '';

    protected $sheetName = 'Sheet1';

    /**
     * @var bool 是否为分页导出
     */
    protected $whetherPage = false;

    /** 表头 */
    abstract protected function setTitle(): array;

    /** 文件名 */
    abstract protected function setFileName(): string;

    /** 导出数据 */
    abstract protected function setExportData(): array;

    abstract protected function beforeExport();

    public function __construct(string $savePath = '')
    {
        $this->path = $savePath;
    }

    /**
     * 自定义表头
     * @return Excel
     */
    protected function customHeader()
    {
        return $this->excel->header(array_values($this->title));
    }

    /**
     * 工作表
     */
    protected function sheetNames(): array
    {
        return [
            'Sheet1'
        ];
    }

    /**
     * 设置从第几行开始写数据
     * @return int
     */
    protected function setLiens()
    {
        return 1;
    }

    public function export()
    {
        $this->beforeExport();
        $this->initialise();
        ob_start();

        foreach ($this->sheetNames() as $sheetName) {
            if ($this->sheetName != $sheetName) {
                $this->excel->addSheet($sheetName);
                $this->sheetName = $sheetName;
            }
            $this->customHeader();
            $this->lines = $this->setLiens();
            /** 分页导出 */
            if ($this->whetherPage) {
                $this->queryDataBatches();
            } else {
                $this->exportData = $this->setExportData();
                $this->render();
            }
        }

        /** 没效果，应该是这个版本不支持，但官方文档上可以这样调用 */
//        if ($this->sheetName != 'Sheet1') {
//            /** 切换到Sheet1工作表并隐藏它 */
//            $this->excel->checkoutSheet('Sheet1')->setCurrentSheetHide();
//        }

        return $this->execution();
    }

    /**
     * 设置渲染字段
     * @return array
     */
    protected function setRenderFields(): array
    {
        return array_keys($this->title);
    }

    /**
     * 分页查询
     */
    protected function queryDataBatches()
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

    protected function render()
    {
        $methodExists = method_exists($this, 'customProcessing');
        $fields       = $this->setRenderFields();
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

    protected function initialise()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->savePath = $this->setDir();
        $this->title    = $this->setTitle();
        $this->fileName = $this->setFileName();
        $excel          = new Excel($this->config($this->savePath));
        $this->excel    = $excel->fileName($this->fileName . '.xlsx');
        if ($this->whetherPage) {
            $this->excel->constMemory($this->fileName . '.xlsx');
        }
    }

    /**
     * 执行
     * @return string|void
     */
    protected function execution()
    {
        $filePath = $this->excel->output();
        /** 不是下载 */
        if (!$this->download) {
            return $filePath; // 返回文件地址 本地文件地址 全路径 例：/www/wwwroot/flash_sign_cloud/console/runtime/SaveLocalFile/xxx.xlsx
        }

        $this->setHeader();

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

    protected function config($savePath)
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

    protected function setDir()
    {
        $dir = variable()->get('runtime_dir', '/');
        if ($this->path) {
            $dir .= $this->path;
        } else {
            $classDir = explode('\\', get_called_class());
            $classDir = array_splice($classDir, -2, 2);
            $dir      .= '/Xlswriter/' . implode('/', $classDir) . '/';
        }

        return $dir;
    }

    protected function setHeader()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment;filename="%s.xlsx"', urlencode($this->fileName)));
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Access-Control-Expose-Headers: Content-Disposition');
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

    /**
     * 将数字列号转换为Excel字母列名
     * @param $column
     * @return string 对应的字母列名
     */
    protected function numberToColumnLetter($column)
    {
        $letter = '';
        while ($column > 0) {
            // 计算当前位的字母
            $remainder = ($column - 1) % 26;
            $letter    = chr(65 + $remainder) . $letter;
            // 处理下一位
            $column = (int)(($column - $remainder) / 26);
        }
        return $letter;
    }
}