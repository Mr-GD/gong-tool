<?php

namespace common\Model\Api;

interface MysqlEventInterface
{

    public function beforeSave();

    public function afterSave();

    public function beforeDelete();

    public function afterDelete();
}
