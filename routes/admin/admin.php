<?php

use App\Http\Controllers\Admin\Login\LandingPageController;
use Illuminate\Support\Facades\Route;

// 登录页面路由
Route::get('LandingPage/index', [LandingPageController::class, 'index']);

Route::get('LandingPage/getRandomImage', [LandingPageController::class, 'getRandomImage']);

Route::post('LandingPage/login', [LandingPageController::class, 'login']);

Route::post('test/index', function () {
    echo '哈喽';
    exit;
});
