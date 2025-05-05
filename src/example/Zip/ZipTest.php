<?php

use gong\tool\Zip\Zip;

/** 压缩文件 */
$needZipDir = '';
$zip        = new Zip();
$zipFileDir = $needZipDir . "/压缩包名.zip";
$zip::zipDir($needZipDir, $zipFileDir);