<?php

namespace common\Tool\ExternalRequest;

use common\Tool\Base\Request\InterfaceRequest;
use gong\tool\base\api\Request\MakeRequest;

/**
 * 韩小韩WebAPI接口
 * https://api.vvhan.com/
 */
class Vvhan extends InterfaceRequest implements MakeRequest
{

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
        return $this->get()
                    ->setRoute('/api/wallpaper/views?type=json')
                    ->request()
        ;
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
