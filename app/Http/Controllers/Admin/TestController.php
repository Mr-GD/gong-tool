<?php

namespace App\Http\Controllers\Admin;

use common\Controller\AdminController;

class TestController extends AdminController
{

    public function index()
    {
        echo 'admin接口';
    }
}
