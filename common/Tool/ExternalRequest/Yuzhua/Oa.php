<?php

namespace common\Tool\ExternalRequest\Yuzhua;

use common\Tool\Base\Request\InterfaceRequest;
use gong\tool\base\api\Request\MakeRequest;
use Illuminate\Support\Facades\Cache;

/**
 * @method $this setPattern(string $pattern) // 设置请求模式
 * @method $this setToken(string $token) // 设置token
 * @method $this setNumber(int $number) // 设置工号
 */
class Oa extends InterfaceRequest implements MakeRequest
{

    public bool $recordLog = true;

    public string $features = '鱼爪OA';
    public int $number;
    public string $pattern = 'test';
    public string $token;

    /**
     * 课程学习统计
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2025/1/3 17:35
     */
    public function courseLearningStatistics()
    {
        return $this->post()
                    ->setParams([
                        'dimension' => 'stage'
                    ])
                    ->setRoute('/api/course-fraction/learning-statistics')
                    ->setRemark('课程学习统计')
                    ->request()
        ;
    }

    /**
     * 离退列表
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2025/1/3 16:59
     */
    public function retreats(array $params = [])
    {
        return $this->get()
                    ->setParams($params)
                    ->setRoute('/api/retreats')
                    ->setRemark('离退列表')
                    ->request()
        ;
    }

    public function setHeaders(): array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->getAuthToken())
        ];
    }

    public function getAuthToken()
    {
        if (!empty($this->token)) {
            return $this->token;
        }

        if (empty($this->number)) {
            return '';
        }

        $cacheKey    = 'yuzhua.oa.token.' . $this->number;
        $accessToken = Cache::get($cacheKey);
        if ($accessToken) {
            return $accessToken;
        }

        $code = $this->getCode();
        return $this->oneself()
                    ->post()
                    ->setParams([
                        'grant_type'    => 'authorization_code',
                        'code'          => $code,
                        'client_id'     => 'oa',
                        'client_secret' => '_q0LEyk5D8RxJX4O_fOh8tIahXjq2pt4',
                        'redirect_uri'  => 'http://oa.yuzhua-test.com/login/'
                    ])
                    ->setCompleteAddress('http://oauth-oa.yuzhua-test.com/token')
                    ->setUserDefinedHeader([])
                    ->setRemark('获取token')
                    ->request(function ($response) use ($cacheKey) {
                        $accessToken = $response['access_token'] ?? '';
                        if ($accessToken) {
                            Cache::put($cacheKey, $accessToken, 7000);
                        }
                        return $accessToken;
                    })
        ;
    }

    /**
     * 获取code码
     * @return mixed
     * @throws \Exception
     * @author 龚德铭
     * @date 2025/1/3 17:46
     */
    public function getCode()
    {
        $clientId     = 'oa';
        $clientSecret = '_q0LEyk5D8RxJX4O_fOh8tIahXjq2pt4';
        $timestamp    = time();
        $random       = uniqid();
        $signature    = md5($clientId . $clientSecret . $timestamp . $random);
        return $this->oneself()
                    ->post()
                    ->setParams([
                        'name' => 'oa'
                    ])
                    ->setRoute('/api/staffs/get-client-code')
                    ->setRemark('获取子平台code码')
                    ->setUserDefinedHeader([
                        'Content-Type' => 'application/json',
                        'X-Client-Id'  => $clientId,
                        'X-Timestamp'  => $timestamp,
                        'X-Random'     => $random,
                        'X-Username'   => 10003,
                        'X-Signature'  => $signature,
                    ])
                    ->request()
        ;
    }

    public function setUrl(): string
    {
        return $this->pattern == 'test' ? 'http://api-oa.yuzhua-test.com' : 'https://api-oa.sudoyu.com';
    }

    public function analyze($response)
    {
        return $response['data'] ?? [];
    }
}
