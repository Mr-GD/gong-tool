<?php

use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

Route::get('test/index', [TestController::class, 'index']);
Route::post('test/checkRequest', [TestController::class, 'checkRequest']);




//Route::group(['prefix' => 'user'], function () {
//    Route::get('/profile', function () {
//        return 'User Profile';
//    });
//    Route::get('/posts', function () {
//        return 'User Posts';
//    });
//});
//再次分组后，最终的接口请求路由是：
///api/user/profile
///api/user/posts
