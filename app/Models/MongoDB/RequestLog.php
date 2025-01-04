<?php

namespace App\Models\MongoDB;

use common\Model\MongoDb;

/**
 * @property string $_id
 * @property string $request_id 请求ID
 * @property string $features 对应功能
 * @property string $url 请求地址
 * @property string $method 请求方式
 * @property int $http_code http状态码
 * @property array $options 提交参数
 * @property array $response 响应体
 * @property string $created_at 请求时间
 * @property int $status 请求结果
 * @property float $time_taken 请求用时
 */
class RequestLog extends MongoDb
{
    public string $collection = 'request_log';
    public array $fields = [
        '_id'        => '_id',
        'request_id' => '请求ID',
        'features'   => '对应功能',
        'url'        => '请求地址',
        'method'     => '请求方式',
        'http_code'  => 'http状态码',
        'options'    => '提交参数',
        'response'   => '响应体',
        'created_at' => '请求时间',
        'status'     => '请求结果',
        'time_taken' => '请求用时',
    ];

}
