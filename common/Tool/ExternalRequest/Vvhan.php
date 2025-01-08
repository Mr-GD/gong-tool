<?php

namespace common\Tool\ExternalRequest;

use common\Tool\Base\Request\InterfaceRequest;
use Exception;

/**
 * 韩小韩WebAPI接口
 * https://api.vvhan.com/
 */
class Vvhan extends InterfaceRequest
{

    public string $features = '韩小韩WebAPI接口';

    public function setHeaders(): array
    {
        return [

        ];
    }

    /**
     * 获取风景图片
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2024/12/31 18:01
     */
    public function getLandscapeImages()
    {
        $response = $this->get()
                         ->setRoute('/api/wallpaper/views?type=json')
                         ->setRemark('获取风景图片')
                         ->request()
        ;
        if (empty($response['url'])) {
            throw new Exception('接口请求失败，图片地址返回异常');
        }
        return $response['url'];
    }

    public function setUrl(): string
    {
        return env('VVHAN_API_URL');
    }

    public function analyze($response)
    {
        return $response;
    }
}
