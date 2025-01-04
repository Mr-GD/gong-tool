<?php

namespace App\Http\Controllers\Admin\Login;

use App\Models\Background\LoginBackgroundPicture;
use common\Controller\AdminController;

class LandingPageController extends AdminController
{
    public function index()
    {
        return view('admin.login.index');
    }

    public function getRandomImage()
    {
        $picture = LoginBackgroundPicture::randomData();
        return $this->response()->success(['url' => $picture->formatStorageFileUrl() ?? '']);
    }

    public function login()
    {
        echo 22222222;
        exit;
    }

}
