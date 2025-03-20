<?php

namespace App\Providers;

use common\Tool\Framework\Loading;
use Illuminate\Support\ServiceProvider;

class StartupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /** 加载框架 */
        Loading::instance()->analysisApplications()->execute();
    }
}
