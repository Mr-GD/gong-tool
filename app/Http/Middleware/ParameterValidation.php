<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        $paths = explode('/', request()->path());
        if (count($paths) > 3) {
            return $next($request);
        }

        list($model, $class, $scenario) = explode('/', request()->path());

        $classDir = sprintf('App\Validate\\%s\\%s', $model, $class . 'Validate');
        var_dump($class::validator([123]));exit;


        return $next($request);
    }
}
