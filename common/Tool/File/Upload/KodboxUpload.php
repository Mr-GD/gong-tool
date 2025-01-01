<?php

namespace common\Tool\File\Upload;

use common\Tool\Base\Request\InterfaceRequest;
use Exception;
use gong\tool\base\api\Request\MakeRequest;
use Illuminate\Support\Facades\Cache;

/** 可道云文件上传 */
class KodboxUpload extends InterfaceRequest implements MakeRequest
{
    public string $features = '文件上传';

    public function setHeaders(): array
    {
        return [

        ];
    }

    /**
     * 模拟表单上传文件
     * @param string $filePath
     * @return string
     * @throws Exception
     * @author 龚德铭
     * @date 2024/12/18 21:16
     */
    public function simulateFormUpload(string $filePath)
    {
        $accessToken   = $this->getAccessToken();
        $basename      = basename($filePath);
        $name          = explode('.', $basename);
        $suffix        = end($name);
        $snowflakeId   = generateSnowflakeId();
        $uploadFileUrl = $this->post()
                              ->setReplenishOptions([
                                  'multipart' => [
                                      [
                                          'name'     => 'file',
                                          'contents' => fopen($filePath, 'r'),
                                          'filename' => $basename,
                                      ],
                                      [
                                          'name'     => 'path',
                                          'contents' => '{source:20}',
                                      ],
                                      [
                                          'name'     => 'name',
                                          'contents' => sprintf('%s_%s.%s', $suffix, $snowflakeId, $suffix),
                                      ],
                                  ]
                              ])
                              ->setRoute('/index.php?explorer/upload/fileUpload&accessToken=' . $accessToken)
                              ->request('模拟表单上传文件')
        ;

        return '/index.php?explorer/index/fileOut&path=' . $uploadFileUrl;
    }

    /**
     * 获取AccessToken
     * @return mixed
     * @throws Exception
     * @author 龚德铭
     * @date 2024/12/17 15:33
     */
    private function getAccessToken()
    {
        $cacheKey    = 'file.upload.accessToken';
        $accessToken = Cache::get($cacheKey);
        if ($accessToken) {
            return $accessToken;
        }
        $route       = sprintf(
            '/?user/index/loginSubmit&name=%s&password=%s',
            env('UPLOAD_KODBOX_USERNAME'),
            env('UPLOAD_KODBOX_PASSWORD')
        );
        $accessToken = $this->get()
                            ->setRoute($route)
                            ->request('获取AccessToken')
        ;

        Cache::put($cacheKey, $accessToken, 3600);
        return $accessToken;
    }

    public function setUrl(): string
    {
        return env('UPLOAD_FILE_KODBOX_API_URL');
    }

    public function analyze($response)
    {
        if (empty($response['code']) || $response['code'] !== true) {
            throw new Exception('文件上传失败 ' . $response['data'] ?? '');
        }

        return $response['info'] ?? '';
    }
}
