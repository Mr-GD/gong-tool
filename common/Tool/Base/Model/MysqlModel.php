<?php

namespace common\Tool\Base\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * php artisan code:models --table=users
 */
class MysqlModel extends Model
{
    public static function instance()
    {
        $static = new static();

        return DB::table($static->getTable());
    }
}
