<?php

namespace App\Service\Common;

use App\Models\Kodbox;
use gong\helper\Data;
use gong\helper\Instance;

class KodboxService
{
    use Data, Instance;

    public function getPathByExt(string $ext)
    {
        return Kodbox::instance()->where('ext', $ext)->value('path');
    }
}
