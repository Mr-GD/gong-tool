<?php

namespace gong\tool\Orm;

use gong\tool\base\api\Orm\ElasticsearchConfigure;
use Vae\PhpElasticsearchOrm\Builder;
use Vae\PhpElasticsearchOrm\Factory;

/**
 * 需要引入 vae/php-elasticsearch-orm 包
 * composer install vae/php-elasticsearch-orm
 * 但由于该包仅支持php 7.4 且与laravel 11冲突，故此移除这个功能
 */
class ElasticsearchOrm extends Factory
{
    public Builder $builder;

    public function __construct(array $config = [])
    {

        $this->builder = self::builder($config);
    }

    public static function find(?ElasticsearchConfigure $configure = null)
    {
        $self = new self($configure->config());
        return $self->builder;
    }
}