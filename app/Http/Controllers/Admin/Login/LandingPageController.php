<?php

namespace App\Http\Controllers\Admin\Login;

use common\Controller\AdminController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LandingPageController extends AdminController
{
    public function index()
    {
        return view('admin.login.index');
    }

    public function getRandomImage()
    {
        return $this->response()->success(['name' => '张三', 'age' => 18]);
    }

    public function login()
    {
        echo 22222222;
        exit;
    }

}
