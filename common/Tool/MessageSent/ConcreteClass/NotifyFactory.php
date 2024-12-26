<?php

namespace common\Tool\MessageSent\ConcreteClass;

use common\tool\MessageSent\Bark;
use gong\tool\base\api\Factory;

class NotifyFactory implements Factory
{
    /**
     *
     * @param $type
     * @return NotifySend
     * @author 龚德铭
     * @date 2024/12/20 13:48
     */
    public static function create($type)
    {
        $class = null;
        switch ($type) {
            case 'bark':
                $class = new Bark();
                break;
            default:
                break;
        }
        return $class;
    }
}
