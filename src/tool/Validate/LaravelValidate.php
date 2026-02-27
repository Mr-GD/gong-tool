<?php

namespace gong\tool\Validate;

use Exception;
use Inhere\Validate\FieldValidation;

//规则配置类似于Laravel

/**
 * 文档地址：https://github.com/inhere/php-validate/wiki/config-rules-like-laravel
 */
abstract class LaravelValidate extends FieldValidation
{
    # 进行验证前处理,返回false则停止验证,但没有错误信息,可以在逻辑中调用 addError 增加错误信息
    public function beforeValidate(): bool
    {
        return true;
    }

    # 进行验证后处理,该干啥干啥
    public function afterValidate(): void
    {
        if ($this->isFail()) {
            $keys    = array_keys($this->translates());
            $values  = array_values($this->translates());
            $message = str_replace($keys, $values, $this->firstError());
            throw new Exception($message);
        }
    }

    /** 验证 */
    public function validator(array $data, string $scene)
    {
        $validate = parent::make($data);
        $scene    = lcfirst(str_replace('action', '', $scene));
        $scenes   = $validate->scenarios();

        if (!isset($scenes[$scene])) {
            return '';
        }
        $validate->setScene($scene);
        return $validate->validate()->firstError();
    }

    /** 自定义验证器的提示消息, 默认消息请看 {@see ErrorMessageTrait::$messages} */
    public function messages(): array
    {
        return [
            'required'           => '请求参数里缺少【{attr}】',
            'string'             => '{attr} 必须是字符串。',
            'regexp'             => '{attr} 不合法。',
            'email'              => '{attr} 格式错误，请核对后重新输入。',
            'int'                => '{attr} 必须是整形数值。',
            'array'              => '{attr} 必须是数组。',
            'number'             => '{attr} 必须是大于0的整数。',
            'distinct'           => '{attr} 数组中的值必须是唯一的。',
            'alphaDash'          => '{attr} 仅包含字母、数字、破折号（ - ）以及下划线（ _ ）。',
            'alphaNum'           => '{attr} 仅包含字母、数字。',
            'date'               => '{attr} 必须是一个正确的日期格式。',
            'url'                => '{attr} 必须是一个正确的链接地址。',
            'requiredWithoutAll' => ' 指定字段【{value0}】没有值，【{attr}】为必填项。',
            'in'                 => '{attr} 取值超出范围：{value0}。',
            'max'                => '{attr} 超出规定长度：{value0}。',
            'float'              => '{attr} 必须是一个浮点数。',
            'customString'       => '{attr} 必须是字符串且不能超出规定长度',
            'customInt'          => '{attr} 必须是int类型的整形数值。',
            'customNonEmpty'     => '{attr} 不能为空或null。',
            'customAmount'       => '{attr} 必须是大于0的数值。',
            'requiredUnless'     => '请求参数里缺少【{attr}】',
            'each'               => '{attr} 必须为数组且值必须为【{value0}】',
            'customEachNumber'   => '{attr} 必须是数组且元素必须为大于0的整数',
            'numList'            => '{attr} 数组里面必须是大于0的数值',
            'intList'            => '{attr} 数组里面必须是整形数值',
            'customIntList'      => '{attr} 数组里面必须是整形数值',
            'dateFormat'         => '{attr} 不满足日期格式【{value0}】',
            'ltField'            => '{attr} 不能大于【{value0}】',
        ];
    }

    /**
     * 字符串验证
     * @param $data
     * @param int $max
     * @return bool
     */
    public function customStringValidator($data, int $max = 0)
    {
        if (!is_string($data)) {
            return false;
        }

        if ($max && mb_strlen($data) > $max) {
            return false;
        }
        return true;
    }

    /**
     * int验证
     * @param $data
     * @param int $max
     * @return bool
     * @date 2023/5/17 9:25
     */
    public function customIntValidator($data, int $max = 0)
    {
        if (!is_int($data)) {
            return false;
        }

        if ($max && $data > $max) {
            return false;
        }
        return true;
    }

    /**
     * 验证金额
     * @param $data
     * @return bool
     */
    public function customAmountValidator($data)
    {
        if (!is_numeric($data)) {
            return false;
        }
        if ($data < 0) {
            return false;
        }
        return true;
    }

    /**
     * 验证数组里的整形数值
     * @param $data
     * @return bool
     */
    public function customIntListValidator($data)
    {

        if (!is_array($data)) {
            return false;
        }

        foreach ($data as $dv) {
            if (!is_int($dv)) {
                return false;
            }

            if ($dv < 0) {
                return false;
            }
        }

        return true;
    }

    public function customEachNumberValidator($data)
    {
        if (!is_array($data)) {
            return false;
        }

        foreach ($data as $dv) {
            if (!is_numeric($dv)) {
                return false;
            }

            if ($dv != intval($dv)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 规则
     */
    abstract public function rules(): array;

    /**
     * 场景
     */
    abstract public function scenarios(): array;

    /**
     * 字段翻译
     */
    abstract public function translates(): array;
}