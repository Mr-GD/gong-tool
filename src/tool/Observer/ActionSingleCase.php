<?php

namespace gong\tool\Observer;

class ActionSingleCase extends Action
{
    public static ?ActionSingleCase $instance = null;
    public static function singleCase()
    {
        if (self::$instance instanceof ActionSingleCase) {
            return self::$instance;
        }

        self::$instance = self::instance();
        return self::$instance;
    }
}