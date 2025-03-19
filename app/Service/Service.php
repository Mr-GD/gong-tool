<?php

namespace App\Service;

use gong\helper\traits\Data;
use gong\helper\traits\Instance;

/**
 * @method $this setParams($params) 设置参数
 */
class Service
{
    use Data, Instance;

    public mixed $params;
}
