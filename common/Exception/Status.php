<?php

namespace common\Exception;

class Status
{
    // Informational 1xx
    const CODE_CONTINUE                        = 100;
    const CODE_SWITCHING_PROTOCOLS             = 101;
    const CODE_SUCCESS                         = 200;
    const CODE_CREATED                         = 210;
    const CODE_ACCEPTED                        = 202;
    const CODE_NON_AUTHORITATIVE_INFORMATION   = 203;
    const CODE_NO_CONTENT                      = 204;
    const CODE_RESET_CONTENT                   = 205;
    const CODE_PARTIAL_CONTENT                 = 206;
    const CODE_MULTIPLE_CHOICES                = 300;
    const CODE_MOVED_PERMANENTLY               = 301;
    const CODE_MOVED_TEMPORARILY               = 302;
    const CODE_SEE_OTHER                       = 303;
    const CODE_NOT_MODIFIED                    = 304;
    const CODE_USE_PROXY                       = 305;
    const CODE_TEMPORARY_REDIRECT              = 307;
    const CODE_BAD_REQUEST                     = 400;
    const CODE_UNAUTHORIZED                    = 401;
    const CODE_PAYMENT_REQUIRED                = 402;
    const CODE_FORBIDDEN                       = 403;
    const CODE_NOT_FOUND                       = 404;
    const CODE_METHOD_NOT_ALLOWED              = 405;
    const CODE_NOT_ACCEPTABLE                  = 406;
    const CODE_PROXY_AUTHENTICATION_REQUIRED   = 407;
    const CODE_REQUEST_TIMEOUT                 = 408;
    const CODE_CONFLICT                        = 409;
    const CODE_GONE                            = 410;
    const CODE_LENGTH_REQUIRED                 = 411;
    const CODE_PRECONDITION_FAILED             = 412;
    const CODE_REQUIRED_ENTITY_TOO_LARGE       = 413;
    const CODE_REQUEST_URI_TOO_LONG            = 414;
    const CODE_UNSUPPORTED_MEDIA_TYPE          = 415;
    const CODE_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const CODE_EXPECTATION_FAILED              = 415;
    const CODE_UNPROCESSABLE_ENTITY            = 422;
    const CODE_INTERNAL_SERVER_ERROR           = 500;
    const CODE_NOT_IMPLEMENTED                 = 501;
    const CODE_BAD_GATEWAY                     = 502;
    const CODE_SERVICE_UNAVAILABLE             = 503;
    const CODE_GATEWAY_TIMEOUT                 = 504;
    const CODE_HTTP_VERSION_NOT_SUPPORTED      = 505;
    const CODE_BANDWIDTH_LIMIT_EXCEEDED        = 509;
    const CODE_PARAMS_ERROR                    = 417;

    private static $phrases = [
        '0'            => '未知错误',
        '100'          => '继续请求',
        '101'          => '切换协议',
        '102'          => '处理中',
        '200'          => '请求成功',
        '201'          => '已创建',
        '202'          => '已接受',
        '203'          => '非权威信息',
        '204'          => '无内容',
        '205'          => '重置内容',
        '206'          => '部分内容',
        '207'          => '多状态',
        '208'          => '已上报',
        '226'          => 'IM已使用',
        '300'          => '多种选择',
        '301'          => '已永久移动',
        '302'          => '临时移动',
        '303'          => '见其他',
        '304'          => '未修改',
        '305'          => '使用代理',
        '307'          => '临时重定向',
        '308'          => '永久重定向',
        '400'          => '请求错误',
        '401'          => '未授权',
        '402'          => '需要付款',
        '403'          => '禁止',
        '404'          => 'URL地址错误',
        '405'          => '请求方法不允许',
        '406'          => '无法接受',
        '407'          => '需要代理验证',
        '408'          => '请求超时',
        '409'          => '冲突',
        '410'          => '不可用',
        '411'          => '长度要求',
        '412'          => '前提条件未满足',
        '413'          => '请求实体过大',
        '414'          => 'URI太长了',
        '415'          => '不支持的媒体类型',
        '416'          => '请求范围不符合',
        '417'          => '期望不满足',
        '418'          => '我是一个茶壶',
        '419'          => '认证已过期',
        '421'          => '错误的请求',
        '422'          => '不可处理的实体',
        '423'          => '锁定',
        '424'          => '失败的依赖',
        '425'          => '太早了',
        '426'          => '需要升级',
        '428'          => '前提要求',
        '429'          => '请求太多',
        '431'          => '请求标头字段太大',
        '444'          => '连接关闭无响应',
        '449'          => '重试',
        '451'          => '法律原因不可用',
        '499'          => '客户端关闭请求',
        '500'          => '服务器内部错误',
        '501'          => '未实现',
        '502'          => '网关错误',
        '503'          => '服务不可用',
        '504'          => '网关超时',
        '505'          => 'HTTP版本不支持',
        '506'          => '变体协商',
        '507'          => '存储空间不足',
        '508'          => '检测到环路',
        '509'          => '超出带宽限制',
        '510'          => '未延期',
        '511'          => '需要网络验证',
        '520'          => '未知错误',
        '521'          => 'Web服务器已关闭',
        '522'          => '连接超时',
        '523'          => '原点无法到达',
        '524'          => '发生超时',
        '525'          => 'SSL握手失败',
        '526'          => '无效的SSL证书',
        '527'          => '轨道炮错误',
        '598'          => '网络读取超时',
        '599'          => '网络连接超时',
        'unknownError' => '未知错误',
    ];

    static function getReasonPhrase($statusCode): string
    {
        return self::$phrases[$statusCode] ?? '未知错误';
    }

}
