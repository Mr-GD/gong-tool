<?php

namespace common\Model;

use common\Model\Api\MysqlEventInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * php artisan code:models --table=users
 */
abstract class MysqlModel extends Model implements MysqlEventInterface
{
    public $timestamps = false;

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
        $tableName  = (new static)->getTable();
        $minId      = DB::table($tableName)->min('id');
        $maxId      = DB::table($tableName)->max('id');
        $randomId   = rand($minId, $maxId);
        $aliasTable = 'random_table';
        return self::instance()
                   ->from($tableName . ' AS ' . $aliasTable)
                   ->where(sprintf('%s.id', $aliasTable), '>=', $randomId)
                   ->orderBy(sprintf('%s.id', $aliasTable))
                   ->first()
        ;
    }

    protected static function boot()
    {
        parent::boot();
        /** 保存前 */
        static::saving(function (self $model) {
            $model->beforeSave(!$model->exists);
        });
        /** 保存后 */
        static::saved(function (self $model) {
            $model->afterSave();
        });
        /** 删除前 */
        static::deleting(function (self $model) {
            $model->beforeDelete();
        });
        /** 删除后 */
        static::deleted(function (self $model) {
            $model->afterDelete();
        });
        /** 修改前 */
        static::updating(function (self $model) {
            $model->beforeSave(false);
        });
        /** 修改后 */
        static::updated(function (self $model) {
            $model->afterSave();
        });
        /** 创建前 */
        static::creating(function (self $model) {
            $model->beforeSave(true);
        });
        /** 创建后 */
        static::created(function (self $model) {
            $model->afterSave();
        });
    }

}
