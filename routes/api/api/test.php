<?php

use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

Route::get('test/index', [TestController::class, 'index']);
Route::post('test/checkRequest', [TestController::class, 'checkRequest']);
