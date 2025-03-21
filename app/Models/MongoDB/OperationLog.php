<?php

namespace App\Models\MongoDB;

use common\Model\MongoDb;

/**
 * @property string $_id
 * @property string $id
 * @property string $request_id 请求ID
 * @property string $url 请求地址
 * @property string $api_doc 接口注释
 * @property string $method 请求方式
 * @property array $options 提交参数
 * @property array $response 响应体
 * @property int $created_at 请求时间
 * @property int $user_type 用户类型
 * @property string $user_account 用户账号
 * @property string $ip IP
 */
class OperationLog extends MongoDb
{
    public string $collection = 'operation_log';
    public array $fields = [
        '_id'          => '_id',
        'id'           => '主键ID',
        'request_id'   => '请求ID',
        'url'          => '接口地址',
        'api_doc'      => '接口注释',
        'method'       => '请求方式',
        'options'      => '提交参数',
        'response'     => '响应体',
        'created_at'   => '请求时间',
        'user_type'    => '用户类型',
        'user_account' => '用户账号',
        'ip'           => '操作IP',
        'ip_analyze'   => 'ip解析',
    ];

}
