<?php

namespace common\Tool\ExternalRequest;

use App\Exceptions\ErrException;
use common\Exception\Code;
use common\Tool\Base\Request\InterfaceRequest;

/**
 * alapi请求
 * https://www.alapi.cn/
 */
class Alapi extends InterfaceRequest
{
    public string $features = 'Alapi';

    public function setHeaders(): array
    {
        return [
            'Content-Type: application/json'
        ];
    }

    public function setUrl(): string
    {
        return env('ALAPI_URL');
    }

    public function analyze($response)
    {
        if (empty($response)) {
            throw new ErrException(Code::TRILATERAL_REQUEST);
        }

        if (empty($response['code']) || empty($response['data']) || $response['code'] != 200) {
            throw new ErrException(Code::TRILATERAL_REQUEST, $response['message'] ?? '');
        }

        return $response['data'];
    }

    /**
     * 身份证信息查询
     * @param string $idCard
     * @return mixed
     * @throws \Exception
     * @date 2025/3/24 11:31
     * https://www.alapi.cn/api/35/api_document
     * {"request_id":"726938435625402369","success":true,"message":"success","code":200,"data":{"address_code":"441282","abandoned":1,"address":"广东省肇庆市罗定市","province":"广东省","city":"肇庆市","county":"罗定市","birthday":"1981-01-01","constellation":"摩羯座","zodiac":"酉鸡","sex":1,"length":18,"age":43},"time":1734275628,"usage":0}
     */
    public function idCard(string $idCard)
    {
        $params = [
            'id'    => $idCard,
            'token' => env('ALAPI_TOKEN')
        ];
        return $this->post()
                    ->setRoute('/api/idcard')
                    ->setParams($params)
                    ->setRemark('身份证信息查询')
                    ->request()
        ;
    }
}
