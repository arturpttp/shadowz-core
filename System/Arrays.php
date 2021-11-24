<?php

namespace Core\System;

class Arrays
{

    public static function merge(): array {
        $arrays = func_get_args();
        $array = [];
        foreach ($arrays as $arr)
            $array = array_merge($arr, $array);
        return $array;
    }

}