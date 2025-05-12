<?php

namespace gong\tool\base\api\Orm;

interface Elasticsearch
{
    /**
     * 索引名称
     */
    public static function indexName() : string;

    /**
     * 字段属性说明
     * @return array
     */
    public function attributes() : array;

    /**
     * ES配置
     */
    public static function configure(): array;
}
