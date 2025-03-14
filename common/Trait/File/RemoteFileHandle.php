<?php

namespace common\Trait\File;

use common\Constant\Storage\Mode;
use common\Tool\File\Upload\KodboxUpload;

trait RemoteFileHandle
{
    /**
     * 格式化存储文件地址
     * @return string
     * @author 龚德铭
     * @date 2025/1/2 20:00
     */
    function formatStorageFileUrl($addressField = 'url')
    {
        if (str_contains($this->{$addressField}, 'http:') || str_contains($this->url, 'https:')) {
            return $this->{$addressField};
        }

        $return = '';
        switch ($this->storage_mode) {
            case Mode::LOCAL:
                $return = $this->{$addressField};
                break;
            case Mode::KODBOX:
                $url    = rtrim($this->{$addressField}, '/');
                $return = env('UPLOAD_FILE_KODBOX_API_URL') . $url;
                $return = $return ? $return . '&accessToken=' . KodboxUpload::instance()->getAccessToken() : '';
                break;
        }

        return $return;
    }
}
