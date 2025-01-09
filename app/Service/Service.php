<?php

namespace App\Service;

use gong\helper\Data;
use gong\helper\Instance;

/**
 * @method $this setParams($params) 设置参数
 */
class Service
{
    use Data, Instance;

    public $params;
}
