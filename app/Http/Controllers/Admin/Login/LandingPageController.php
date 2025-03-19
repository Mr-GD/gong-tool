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

    /**
     * 获取随机背景图片
     * @return \Illuminate\Http\JsonResponse
     * @date 2025/3/19 11:19
     */
    public function getRandomImage()
    {
        $picture = LoginBackgroundPicture::randomData();
        return $this->response()->success(['url' => $picture->formatStorageFileUrl() ?? '']);
    }

    public function login()
    {
        $params = $this->getBodyParams();

        return $this->response()->success(['access_token' => md5(time())]);
    }

}
