<?php

namespace common\Observe\Artisan;

use gong\tool\base\abs\ObserverAbs;
use Illuminate\Console\Events\CommandStarting;

class ServeStarted extends ObserverAbs
{

    public CommandStarting $event;

    public function watch()
    {
        $docs     = $this->handleInterfaceDoc();
        $redisKey = globalVariable()->getVariable('redis_prefix') . 'api.docs';
        redis()->hMset($redisKey, $docs);
    }

    public function handleInterfaceDoc()
    {
        $controllers = $this->handleControllers();
        $docs        = [];
        foreach ($controllers as $controller) {
            $class   = new \ReflectionClass(new $controller);
            $methods = $class->getMethods();
            foreach ($methods as $method) {
                if ($method->class != $controller) {
                    continue;
                }
                $docComment = $method->getDocComment();
                $key        = $controller . '@' . $method->getName();
                $docs[$key] = $docComment ? $this->handleComment($docComment) : $method->getName();
            }
        }

        return $docs;
    }

    public function handleControllers()
    {
        $files       = recursiveGlob(app_path('Http/Controllers'));
        $controllers = [];
        foreach ($files as $file) {
            $controllers[] = $this->trim($file);
        }
        return $controllers;
    }

    public function trim($file)
    {
        $temp = substr($file, strpos($file, '/app/Http/Controllers/'));
        $temp = str_replace('/app', 'App', $temp);
        $temp = str_replace('/', '\\', $temp);
        return rtrim(ltrim($temp, '\\'), '.php');
    }

    public function handleComment($comment)
    {
        $lines       = explode("\n", $comment);
        $secondLine  = $lines[1];
        $cleanedLine = ltrim($secondLine, " *");
        preg_match_all('/[\x{4e00}-\x{9fa5}]+/u', $cleanedLine, $matches);
        return implode('', $matches[0]);
    }
}
