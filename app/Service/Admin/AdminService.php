<?php

namespace App\Service\Admin;

use App\Models\Admin\Admin;
use App\Service\Service;

class AdminService extends Service
{

    /**
     * 创建后台账号
     * @return Admin
     * @author 龚德铭
     * @date 2025/1/9 21:58
     */
    public function create()
    {
        return Admin::instance()->create($this->params);
    }
}
