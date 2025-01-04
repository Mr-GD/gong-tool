<?php

namespace common\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * php artisan code:models --table=users
 */
class MysqlModel extends Model
{
    public static function instance()
    {
        return static::query();
    }

    /**
     * 随机一条数据
     * @return static
     * @author 龚德铭
     * @date 2025/1/4 16:13
     */
    public static function randomData()
    {
        $tableName = (new static)->getTable();
        $minId     = DB::table($tableName)->min('id');
        $maxId     = DB::table($tableName)->max('id');
        $randomId  = rand($minId, $maxId);
        return self::instance()
                   ->from($tableName . ' AS random_table')
                   ->where('random_table.id', '>=', $randomId)
                   ->orderBy('random_table.id')
                   ->first()
        ;
    }
}
