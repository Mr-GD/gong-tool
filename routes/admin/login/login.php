<?php

use App\Http\Controllers\Admin\Login\LandingPageController;
use Illuminate\Support\Facades\Route;

// 登陆页面
Route::get('LandingPage/index', [LandingPageController::class, 'index']);

Route::post('LandingPage/getRandomImage', [LandingPageController::class, 'getRandomImage']);

Route::post('LandingPage/login', [LandingPageController::class, 'login']);
