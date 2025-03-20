<?php

namespace common\helpers;

use common\Tool\Redis;
use gong\helper\GlobalVariable;
use gong\helper\traits\Data;
use gong\tool\base\abs\SingleCase;


/**
 * @method \Redis redis()
 * @method GlobalVariable value();
 */
class Tool extends SingleCase
{
    use Data;
    private \Redis $redis;
    private GlobalVariable $value;
    public function initialise()
    {
        $this->redis = Redis::instance()->redis;
        $this->value = globalVariable();
    }


    /**
     * 遍历获取目录文件
     * @param $pattern
     * @return array
     * @date 2025/3/20 09:39
     */
    public function recursiveGlob($pattern)
    {
        $allFiles = [];
        $files    = glob($pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                $allFiles[] = $file;
            }

            if (is_dir($file)) {
                $subFiles = $this->recursiveGlob($file . '/*');
                $allFiles = array_merge($allFiles, $subFiles);
            }
        }
        return $allFiles;
    }

}
