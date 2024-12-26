<?php

namespace common\Console;

use Illuminate\Console\Command;

class BaseCommand extends Command
{

    /**
     * 参数解析
     * @author 龚德铭
     * @date 2024/12/5 22:39
     */
    public function analyzeParameters()
    {
        $inputs = $this->input->getArguments();
        foreach ($inputs as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }


}
