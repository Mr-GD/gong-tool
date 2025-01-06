<?php
// 登陆页面
use Illuminate\Support\Facades\Route;

Route::get('home/index', function () {
    return view('admin.home.dashboard');
});
