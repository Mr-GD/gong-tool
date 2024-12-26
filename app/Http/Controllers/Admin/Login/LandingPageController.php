<?php

namespace App\Http\Controllers\Admin\Login;

use common\Controller\AdminController;

class LandingPageController extends AdminController
{
    public function index()
    {
        return view('admin.login.index');
//        return view('admin.login.index', [
//            'csrfToken' => csrf_token()
//        ]);
    }

    public function getRandomImage(){
        return '获取随机图片';
    }

    public function login()
    {
        echo 22222222;
        exit;
    }

}
