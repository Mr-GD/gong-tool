<?php

namespace gong\tool\base\api\Request;

interface MakeRequest
{
    function setHeaders(): array;

    function setUrl(): string;

    function analyze($response);

    function afterRequest();

    function fail();
}