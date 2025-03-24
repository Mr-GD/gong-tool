<?php

namespace common\Tool\ExternalRequest;

use App\Exceptions\ErrException;
use common\Exception\Code;
use common\Tool\Base\Request\InterfaceRequest;
use Illuminate\Support\Str;

/**
 * 实用工具箱
 * https://www.idcd.com/
 */
class PracticalToolKit extends InterfaceRequest
{

    public string $features = '实时工具箱';

    public int $time;
    public string $nonce;

    public function __construct(bool $debug = false)
    {
        parent::__construct($debug);
        $this->time  = time();
        $this->nonce = Str::password();
    }

    public function setHeaders(): array
    {
        return [
            'ClientID'        => env('PRACTICAL_TOOL_CLIENT_ID'),
            'SignatureMethod' => 'HmacSHA256',
            'Nonce'           => $this->nonce,
            'Timestamp'       => time(),
            'Signature'       => $this->signatureMethod(),
        ];
    }

    public function signatureMethod()
    {
        $plainText = env('PRACTICAL_TOOL_CLIENT_ID') . $this->nonce . $this->time . 'HmacSHA256';
        return hash_hmac('sha256', $plainText, env('PRACTICAL_TOOL_CLIENT_SECRET'));
    }

    public function setUrl(): string
    {
        return env('PRACTICAL_TOOL_KIT_URL');
    }

    public function analyze($response)
    {
        if (empty($response['code']) || $response['code'] != 200 || empty($response['data'])) {
            throw new ErrException(Code::TRILATERAL_REQUEST, $response['message'] ?? '');
        }

        return $response['data'];
    }

    /**
     * IP查询
     * @param string $ip
     * @return mixed
     * @throws \Exception
     * @date 2025/3/24 16:30
     * https://www.idcd.com/docs/open-api/ip
     * response:{"status": true,"code": 200,"message": "Success","request_id": "467f3111-e469-4490-b3ca-5819be2c236d","data": {"ip": "125.71.160.214","country": "中国","region": "四川","city": "成都市","county": "温江区","isp": "电信","area": "中国四川成都市 电信/(温江)电信","long": "","lat": ""}}
     */
    public function ip(string $ip)
    {
        return $this->get()
                    ->setRemark('IP查询')
                    ->setRoute('/api/ip?ip=' . $ip)
                    ->request()
        ;
    }

    /**
     * 历史上的今天
     * @param string $date
     * @return mixed
     * @throws \Exception
     * @date 2025/3/24 16:40
     * https://www.idcd.com/docs/open-api/today-in-history
     * response:{"status": true,"code": 200,"message": "Success","request_id": "1a8091da-380c-4f3e-a933-73da4e29161d","data": [{"year": 280,"month": 5,"day": 1,"data": "西晋将领王濬兵临建业，吴帝孙皓出降，立国52年，历五帝的孙吴宣告灭亡，三国时代正式结束。","date": "280年5月1日"}]}
     */
    public function todayInHistory(string $date = '')
    {
        return $this->get()
                    ->setParams([
                        'date' => $date ?: date('Y-m-d'),
                    ])
                    ->setRemark('历史上的今天')
                    ->setRoute('/api/today-in-history')
                    ->request()
        ;
    }

    /**
     * 人物肖像抠图去背景
     * @param string $url
     * @return mixed
     * @throws \Exception
     * @date 2025/3/24 17:28
     * https://www.idcd.com/docs/open-api/image-remove-bg
     * response:{"status": true,"code": 200,"message": "OK","request_id": "f70d6168-d494-4aa7-a443-e57f314b9c4e","data": {"url": "https://open.19981.com/files/remove_bg/output_download_image_20250216195826.png"}}
     */
    public function imageRemoveBg(string $url)
    {
        return $this->post()
                    ->setRemark('人物肖像抠图去背景')
                    ->setRoute('/api/image-remove-bg')
                    ->setTimeout(10)
                    ->setParams([
                        'image_url' => $url
                    ])
                    ->request()
        ;
    }
}
