<?php

namespace gong\helper\traits;

trait AssignParameter
{
    protected function assignParameter($data)
    {
        foreach ($data as $dk => $dv) {
            $key = explode('_', $dk);
            $key = array_map('ucfirst', $key);
            $key = implode('', $key);
            $key = lcfirst($key);
            if (property_exists($this, $key)) {
                $this->{$key} = $dv;
            }
        }
    }
}