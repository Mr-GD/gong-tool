<?php

use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

Route::post('test/checkRequest', [TestController::class, 'checkRequest']);

Route::get('test/index', function () {
    dd('这个是API的GET请求');
});
