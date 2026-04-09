<?php

namespace gong\tool\Validate;

/**
 * 验证器
 * 基于think 验证器规则
 * https://doc.thinkphp.cn/v8_0/validator.html
 */
abstract class Validate extends \think\Validate
{

    /**
     * @return array
     * @doc 规则
     */
    abstract protected function regulation(): array;

    /**
     * @return array
     * @doc 场景
     */
    abstract protected function scenarios(): array;

    /**
     * @return array
     * @doc 字段翻译
     */
    abstract protected function translates(): array;

    /**
     * @param $data
     * @param string $scene
     * @doc 验证
     * 实际使用时最好重写这个方法，便于使用框架的导演抛出
     */
    public static function validator($data, string $scene = '')
    {
        $make          = validate(static::class);
        $make->rule    = $make->regulation();
        $make->message = $make->translates();
        $make->scene   = $make->scenarios();
        if ($scene) {
            $make->scene($scene);
        }
        $make->check($data);
    }
}