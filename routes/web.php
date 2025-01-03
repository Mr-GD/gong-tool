<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/** admin web页面路由 */
Route::prefix('admin')->group(function () {
    $dir = __DIR__ . '/web/admin/';
    requireAllPhpFiles($dir);
});

/** api web页面路由 */
Route::prefix('api')->group(function () {
    $dir = __DIR__ . '/web/api/';
    requireAllPhpFiles($dir);
});
