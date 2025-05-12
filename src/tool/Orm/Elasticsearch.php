<?php

namespace gong\tool\Orm;

use Vae\PhpElasticsearchOrm\Builder;
use Vae\PhpElasticsearchOrm\Factory;
use gong\tool\base\api\Orm\Elasticsearch as ElasticsearchApi;

abstract class Elasticsearch extends Factory implements ElasticsearchApi
{
    public Builder $builder;

    public function __construct(array $config = [])
    {
        $this->builder = self::builder($config);
    }

    /**
     * test
     */
//    public static function configure(): array
//    {
//        $config             = require_once base_path('vendor') . '/vae/php-elasticsearch-orm/config/elasticsearch.php';
//        $config['hosts']    = [
//            'service.local.com:9200'
//        ];
//        $config['username'] = 'goods';
//        $config['password'] = 'ggq!5201314';
//        return $config;
//    }

    public static function find()
    {
        $self = new static(static::configure());
        return $self->builder->index(static::indexName());
    }
}
