<?php

namespace common\Model\Api;

interface MysqlEventInterface
{

    public function beforeSave(bool $isNewRecord);

    public function afterSave();

    public function beforeDelete();

    public function afterDelete();
}
