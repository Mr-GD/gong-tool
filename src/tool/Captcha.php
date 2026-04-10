<?php

namespace gong\tool;

use gong\helper\traits\Data;
use gong\helper\traits\Make;

/**
 * @method string getCode() 获取验证码
 */
class Captcha
{
    use Make, Data;

    protected ?string $code;

    public function __construct(?string $code = null)
    {
        $this->code = $code ?: $this->_createCode();
    }

    public function render()
    {
        $code = (string)$this->code;
        $len  = strlen($code);

        // 画布尺寸
        $w = 160;
        $h = 60;
        $im = imagecreatetruecolor($w, $h);

        // 浅灰色背景
        $bg = imagecolorallocate($im, 240, 240, 240);
        imagefill($im, 0, 0, $bg);

        // 超多干扰线
        for ($i = 0; $i < 12; $i++) {
            $c = imagecolorallocate($im, mt_rand(100, 180), mt_rand(100, 180), mt_rand(100, 180));
            imageline($im, mt_rand(0, $w), mt_rand(0, $h), mt_rand(0, $w), mt_rand(0, $h), $c);
        }

        // 超多噪点
        for ($i = 0; $i < 300; $i++) {
            $c = imagecolorallocate($im, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
            imagesetpixel($im, mt_rand(0, $w), mt_rand(0, $h), $c);
        }

        // 逐个字符绘制：随机位置、随机倾斜、随机大小
        $x = 25;
        for ($i = 0; $i < $len; $i++) {
            $c = imagecolorallocate($im, mt_rand(0, 80), mt_rand(0, 80), mt_rand(0, 80));
            $font = mt_rand(4, 6);
            $y = mt_rand(15, 35);
            // 单个字符错开，更乱
            imagestring($im, $font, $x + mt_rand(-3, 3), $y, $code[$i], $c);
            $x += 30;
        }

        // 再加一层弧形干扰线
        for ($i = 0; $i < 3; $i++) {
            $c = imagecolorallocate($im, mt_rand(120, 180), mt_rand(120, 180), mt_rand(120, 180));
            imagearc($im, mt_rand(0, $w), mt_rand(0, $h), mt_rand(30, 100), mt_rand(20, 80), mt_rand(0, 180), mt_rand(180, 360), $c);
        }

        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        imagedestroy($im);

        return 'data:image/png;base64,' . base64_encode($data);
    }

    private function _createCode()
    {
        $chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // 去掉易混淆的 0/O, 1/I, 2/Z
        $code  = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $code;
    }
}