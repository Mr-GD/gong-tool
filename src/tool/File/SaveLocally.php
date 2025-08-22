<?php

namespace gong\tool\File;


use gong\helper\traits\Data;
use gong\helper\traits\Instance;
use gong\tool\base\api\Execute;

/**
 * 本地保存文件
 * @method $this setCatalogue(string $catalogue) 设置保存目录
 */
class SaveLocally implements Execute
{
    use Instance, Data;

    /**
     * @var string
     */
    public $catalogue;

    public $filePath;

    public function __construct(string $fileUrl)
    {
        $this->filePath = $fileUrl;
    }

    public function execute()
    {
        $this->loadCatalogue();
        $basename = basename($this->filePath);
        $fileUrl  = $this->catalogue . $basename;
        file_put_contents($fileUrl, file_get_contents($this->filePath));
        return $fileUrl;
    }

    public function loadCatalogue()
    {
        if ($this->catalogue) {
            return;
        }

        $this->catalogue = '/runtime/SaveLocally/';
        if (!is_dir($this->catalogue)) {
            @mkdir($this->catalogue, 0777, true);
        }
    }
}