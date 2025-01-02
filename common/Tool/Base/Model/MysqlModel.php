<?php

namespace common\Tool\Base\Model;

use common\Constant\Storage\Mode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * php artisan code:models --table=users
 */
class MysqlModel extends Model
{
    public static function instance()
    {
        return static::query();
    }

    /**
     * 格式化存储文件地址
     * @return string
     * @author 龚德铭
     * @date 2025/1/2 20:00
     */
    function formatStorageFileUrl()
    {
        if (str_contains($this->url, 'http:') || str_contains($this->url, 'https:')) {
            return $this->url;
        }

        $return = '';
        switch ($this->storage_mode) {
            case Mode::LOCAL:
                print_r($this);exit;
                $return = $this->url;
                break;
            case Mode::KODBOX:
                $return = env('UPLOAD_FILE_KODBOX_API_URL') . $this->url;
                break;
        }

        return $return;
    }
}
