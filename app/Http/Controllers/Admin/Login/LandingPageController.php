<?php

namespace App\Http\Controllers\Admin\Login;

use App\Models\Background\LoginBackgroundPicture;
use common\Controller\BaseController;

class LandingPageController extends BaseController
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
        return $this->response()->success(['access_token' => md5(time())]);
    }

}
