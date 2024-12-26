<?php

namespace api;

use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

Route::post('test/checkRequest', [TestController::class, 'checkRequest']);
