<?php

namespace common\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * php artisan code:models --table=users
 */
class MysqlModel extends Model
{
    public static function instance()
    {
        return static::query();
    }
}
