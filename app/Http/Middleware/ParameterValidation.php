<?php

namespace App\Http\Middleware;

use Closure;
use gong\tool\Validate\LaravelValidate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParameterValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        $paths = explode('/', request()->path());
        if (count(array_filter($paths)) > 3) {
            return $next($request);
        }

        list($model, $class, $scenario) = explode('/', request()->path());

        $classDir = sprintf('App\Validate\\%s\\%s', ucfirst($model), ucfirst($class . 'Validate'));
        if (class_exists($classDir)) {
            /** @var LaravelValidate $class */
            $class = new $classDir;
            $class->validator($request->all(), $scenario);
        }

        return $next($request);
    }
}
