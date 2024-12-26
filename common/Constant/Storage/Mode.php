<?php

namespace common\Constant\Storage;

class Mode
{

    const LOCAL = 1;

    const KODBOX = 2;

    const LABELS = [
        self::LOCAL => '本地',
        self::KODBOX => '可道云',
    ];
}
