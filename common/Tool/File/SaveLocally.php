<?php

namespace common\Tool\File;

use gong\helper\traits\Data;
use gong\helper\traits\Instance;

/**
 * @method $this setRemoteUrl(string $url) //设置远程地址
 */
class SaveLocally
{
    use Data, Instance;

    /** @var string 远程地址 */
    public string $remoteUrl;

    public string $localUrl;

    public function execute()
    {
        $basename = basename($this->remoteUrl);
        list(, $ext) = explode('.', $basename);
        $this->createSaveDir($ext);
        $file = $this->localUrl . $basename;
        file_put_contents($file, file_get_contents($this->remoteUrl));
        return $file;
    }

    public function createSaveDir(string $ext)
    {
        $dirFile = tool()->value()->get('runtime_dir') . 'SaveLocally/' . $ext;
        if (!is_dir($dirFile)) {
            mkdir($dirFile, 0777, true);
        }

        $this->localUrl = $dirFile . '/';
    }
}
