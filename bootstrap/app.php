<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

//frameworkLoading();

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web: __DIR__ . '/../routes/web.php',
                      commands: __DIR__ . '/../routes/console.php',
                      health: '/up',
                  )
                  ->withRouting(
                      api: __DIR__ . '/../routes/admin/admin.php',
                      health: '/up',
                      apiPrefix: 'admin',
                  )
                  ->withRouting(
                      api: __DIR__ . '/../routes/api/api.php',
                      health: '/up',
                  )
                  ->withMiddleware(function (Middleware $middleware) {
                      //
                  })
                  ->withExceptions(function (Exceptions $exceptions) {
                      //
                  })->create()
;
