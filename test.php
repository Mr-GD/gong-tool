<?php
// 引入 Composer 的自动加载文件
require_once __DIR__ . '/vendor/autoload.php';

use gong\tool\Log\Log;

\gong\example\Curl\RequestTest::instance()
                              ->get()
                              ->setRoute('/api/dailyEnglish')
                              ->setParams([
                                  'type' => 'sj'
                              ])
                              ->request()
;