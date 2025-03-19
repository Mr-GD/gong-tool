<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 请使用官方推荐的注册事件和监听器
         * php artisan make:event //注册事件
         * php artisan make:listener //注册监听器
         */
        /** 手动注册事件 */
//        Event::listen([
//            RequestHandled::class, //事件
//            ControllerHasFinishedExecuting::class, //监听器
//        ]);
    }
}
