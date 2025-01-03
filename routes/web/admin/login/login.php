<?php

// 登陆页面
use App\Http\Controllers\Admin\Login\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('LandingPage/index', [LandingPageController::class, 'index']);
