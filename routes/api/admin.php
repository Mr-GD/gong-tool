<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    $dir = __DIR__ . '/admin/';
    requireAllPhpFiles($dir);
});

