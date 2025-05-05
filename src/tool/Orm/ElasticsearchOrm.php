<?php

namespace gong\tool\Orm;

use gong\tool\base\api\Orm\ElasticsearchConfigure;
use Vae\PhpElasticsearchOrm\Builder;
use Vae\PhpElasticsearchOrm\Factory;

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