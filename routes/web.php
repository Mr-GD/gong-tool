<?php

use App\Http\Controllers\Admin\Login\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 登陆页面
Route::get('/LandingPage/index', [LandingPageController::class, 'index']);
