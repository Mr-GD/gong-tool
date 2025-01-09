<?php

namespace App\Validate\Admin;

use gong\tool\Validate\LaravelValidate;

class LandingPageValidate extends LaravelValidate
{

    public function rules(): array
    {
        return [
            ['username', 'required|string'],
            ['password', 'required|string'],
            ['email', 'string'],
        ];
    }

    public function scenarios() : array
    {
        return [
            'login' => [
                'username', 'password'
            ]
        ];
    }

    public function translate(): array
    {
        return [
            'username'     => '用户名',
            'password'     => '密码',
            'email'        => '邮箱',
            'email_verify' => '邮箱验证结果',
        ];
    }

    public function methodMessage(): array
    {
        return [

        ];
    }
}
