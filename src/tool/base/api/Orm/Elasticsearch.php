<?php

namespace gong\tool\base\api\Orm;

use gong\tool\Orm\ElasticsearchOrm;

interface Elasticsearch
{
    /**
     * 索引名称
     * @return ElasticsearchOrm
     */
    public function index() : ElasticsearchOrm;

    /**
     * 字段属性说明
     * @return array
     */
    public function attributes() : array;

    /**
     * ES配置
     * @return ElasticsearchConfigure
     */
    public function configure(): ElasticsearchConfigure;
}