<?php

namespace common\Model;


use common\Tool\Base\Traits\CommonConst;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Eloquent\Model;

class MongoDb extends Model
{
    use CommonConst;

    /** @var string 指明集合名称 */
    public string $collection;
    protected $connection = 'mongodb';
    protected $primaryKey = '_id';

    protected $guarded = []; // 在create方法不能被赋值的字段

    /**
     * @var array 集合字段
     */
    public array $fields = [

    ];

    /**
     * 原始操作
     * @return Builder
     * @author 龚德铭
     * @date 2024/12/19 23:17
     */
    public static function original($dateSeparator = false)
    {
        $static     = new static();
        $collection = $static->collection;
        if ($dateSeparator) {
            $collection .= '_' . date('Y-m');
        }

        return DB::connection('mongodb')->table($collection);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @author 龚德铭
     * @date 2024/12/19 23:25
     */
    public static function instance()
    {
        return static::query();
    }
}
