<?php

namespace gong\tool\base\api;

interface ValidateInterface
{
    public function beforeValidate(): bool;

    public function translate(): array;

    public function scenarios(): array;

    public function rules(): array;
}