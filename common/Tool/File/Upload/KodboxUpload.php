<?php

namespace common\Tool\File\Upload;

use App\Models\Kodbox;
use App\Service\Common\KodboxService;
use common\Tool\Base\Request\InterfaceRequest;
use Exception;
use gong\tool\base\api\Request\MakeRequest;
use Illuminate\Support\Facades\Cache;

/** 可道云文件上传 */
class KodboxUpload extends InterfaceRequest implements MakeRequest
{
    public bool $recordLog = true;

    public string $features = '可道云';

    public string $catalogue = '{source:20}';

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
        $basename      = basename($filePath);
        $name          = explode('.', $basename);
        $suffix        = end($name);
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
                                          'contents' => $this->mkDir($suffix),
                                      ],
                                      [
                                          'name'     => 'name',
                                          'contents' => sprintf('%s_%s.%s', $suffix, generateSnowflakeId(), $suffix),
                                      ],
                                  ]
                              ])
                              ->setRoute('/index.php?explorer/upload/fileUpload&accessToken=' . $this->getAccessToken())
                              ->setRemark('模拟表单上传文件')
                              ->request()
        ;

        return '/index.php?explorer/index/fileOut&path=' . $uploadFileUrl;
    }

    /**
     * 创建文件夹
     * @param $dir
     * @return mixed
     * @throws Exception
     * @author 龚德铭
     * @date 2025/1/2 11:46
     */
    public function mkDir($dir)
    {
        $dirs          = explode('/', $dir);
        $path          = '';
        $rootDirectory = $this->catalogue;
        foreach ($dirs as $catalogue) {
            $path = KodboxService::instance()->getPathByExt($dir);
            if ($path) {
                continue;
            }

            $path = $rootDirectory = $this->oneself()
                                  ->setFormParams([
                                      'path' => $rootDirectory . '/' . $catalogue,
                                  ])
                                  ->setRoute('/index.php?explorer/index/mkdir&accessToken=' . $this->getAccessToken())
                                  ->setRemark('创建文件夹')
                                  ->request()
            ;

            Kodbox::instance()->insert([
                'ext'        => $dir,
                'path'       => $path,
                'created_at' => time(),
            ]);
        }

        return $path;
    }

    /**
     * 获取AccessToken
     * @return mixed
     * @throws Exception
     * @author 龚德铭
     * @date 2024/12/17 15:33
     */
    public function getAccessToken()
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
        $accessToken = $this->oneself()
                            ->setRoute($route)
                            ->setRemark('获取AccessToken')
                            ->request()
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
            throw new Exception($this->features . '失败 ' . $response['data'] ?? '');
        }

        return $response['info'] ?? '';
    }
}
