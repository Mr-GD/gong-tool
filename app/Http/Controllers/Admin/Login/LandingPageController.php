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
        echo 111111111111;exit;
    }

    public function login()
    {
        echo 22222222;
        exit;
    }

}
