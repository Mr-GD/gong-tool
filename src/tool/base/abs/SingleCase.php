<?php

namespace gong\tool\base\abs;

use \gong\tool\base\api\SingleCase as SingleCaseApi;

abstract class SingleCase implements SingleCaseApi
{
    use \gong\helper\traits\SingleCase;

    public function __construct()
    {
        $this->initialise();
    }

}
