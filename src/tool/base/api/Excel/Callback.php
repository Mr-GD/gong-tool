<?php

namespace gong\tool\base\api\Excel;

use gong\tool\base\abs\Excel\Export;

interface Callback
{
    public function callable(Export $export);
}