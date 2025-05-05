<?php

namespace gong\tool\base\api\Request;

interface MakeRequest
{
    public function get();

    public function post();

    public function put();

    public function delete();

    public function setHeaders(): array;

    public function setUrl(): string;

    public function analyze($response);

    public function afterRequest();

    public function exceptionNotify();
}