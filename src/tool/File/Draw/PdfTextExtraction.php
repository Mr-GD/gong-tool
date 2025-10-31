<?php

namespace gong\tool\File\Draw;

use gong\helper\traits\Make;
use gong\helper\traits\Params;
use gong\tool\base\api\Execute;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Page;
use Smalot\PdfParser\Parser;

/**
 * Pdf 文本提取
 * @method $this setKeyword($keyword) 设置关键字
 * @method $this setCharsAfter(int $charsAfter) 设置字符后
 * @method $this setFileSaveCallable(callable $fileSaveCallable) 设置远程文件保存回调
 * @method $this setReplaceStr(mixed $replaceStr) 设置替换字符串
 *
 */
class PdfTextExtraction implements Execute
{

    use Make, Params;

    /**
     * @var Document
     */
    public $pdf;

    public $keyword;

    public $charsAfter = 100;

    public $page = null;

    public $localFileAddress = [];

    public $fileSaveCallable = null;

    public $replaceStr;

    public function __construct()
    {
    }

    public function loadPdf($filePath)
    {
        /** 远程文件 */
        if (str_contains($filePath, 'https://') || str_contains($filePath, 'http://')) {
            if (empty($this->fileSaveCallable)) {
                throw new \Exception('请设置远程文件保存回调');
            }
            /** 保存本地 */
            $this->localFileAddress[] = $filePath = ($this->fileSaveCallable)($filePath);
        }

        $parser    = new Parser();
        $this->pdf = $parser->parseFile($filePath);
        return $this;
    }

    public function execute()
    {
        $pages  = $this->pdf->getPages();
        $result = '';

        if (is_null($this->page)) {
            foreach ($pages as $page) {
                if (!empty($result)) {
                    break;
                }
                $result = $this->mateKeyword($page);
            }
        } else {
            $page   = $pages[$this->page] ?? null;
            $result = $this->mateKeyword($page);
        }

        return $result;
    }

    /**
     * 匹配关键词
     * @param Page|null $page
     * @return string
     */
    public function mateKeyword(?Page $page = null)
    {
        if (empty($page)) {
            return '';
        }

        $text    = $page->getText();
        $pos     = 0;
        $results = '';
        if (($pos = stripos($text, $this->keyword, $pos)) !== false) {
            $start      = $pos + strlen($this->keyword);
            $results    = substr($text, $start, $this->charsAfter);
            $this->page = $page->getPageNumber();
        }

        return $this->formatText($results);
    }

    /**
     * 格式化文本
     * @param $text
     * @return string
     */
    public function formatText($text)
    {
        if (empty($text)) {
            return '';
        }
        $text = explode(PHP_EOL, $text);
        $text = implode(' ', $text);
        if (!empty($this->replaceStr)) {
            $text = str_replace($this->replaceStr, '', $text);
        }
        $text = explode(' ', $text);
        $text = array_filter($text);
        $text = reset($text);
        return trim($text);
    }

    public function __destruct()
    {
        foreach ($this->localFileAddress as $fileAddress) {
            @unlink($fileAddress);
        }
    }
}