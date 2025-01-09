<?php

use App\Exceptions\Handler;
use common\Tool\Framework\Loading;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web: __DIR__ . '/../routes/web.php',
                      api: [
                          __DIR__ . '/../routes/api/api.php',
                          __DIR__ . '/../routes/api/admin.php'
                      ],
                      commands: __DIR__ . '/../routes/console.php',
                      health: '/up',
                      apiPrefix: ''
                  )
                  ->withMiddleware(function (Middleware $middleware) {
                      Loading::instance()->loadMiddleware($middleware);
                  })
                  ->withExceptions(function (Exceptions $exceptions) {
                      (new Handler($exceptions))->handle();
                  })->create()
;
