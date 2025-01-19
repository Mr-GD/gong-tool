<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrException;
use Closure;
use common\Exception\Code;
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
            try {
                $class->validator($request->all(), $scenario);
            } catch (\Exception $e) {
                throw new ErrException(Code::PARAMS_ERROR, $e->getMessage());
            }
        }

        return $next($request);
    }
}
