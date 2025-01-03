<?php

use App\Http\Controllers\Admin\Login\LandingPageController;
use Illuminate\Support\Facades\Route;

//Route::get('/LandingPage/index', [LandingPageController::class, 'index']);

Route::post('LandingPage/getRandomImage', [LandingPageController::class, 'getRandomImage']);

Route::post('LandingPage/login', [LandingPageController::class, 'login']);
