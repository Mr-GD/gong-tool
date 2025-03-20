<?php

namespace common\helpers;

use common\Tool\Redis;
use gong\helper\traits\Data;
use gong\tool\base\abs\SingleCase;

/**
 * @method \Redis redis()
 */
class Tool extends SingleCase
{
    use Data;
    private \Redis $redis;
    public function initialise()
    {
        $this->loadShineUpon();
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

    /**
     * 加载映射数据
     * @date 2025/3/20 11:05
     */
    public function loadShineUpon()
    {
        $this->redis = Redis::instance()->redis;
    }

}
