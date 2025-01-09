<?php

namespace App\Service\Common;

use App\Models\Kodbox;
use App\Service\Service;

class KodboxService extends Service
{

    public function getPathByExt(string $ext)
    {
        return Kodbox::instance()->where('ext', $ext)->value('path');
    }
}
