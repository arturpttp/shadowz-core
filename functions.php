<?php

use Core\Application;

function pre($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}
function replaceAll(string $string, array $list, string $replaceTo = '') : string {
    foreach ($list as $item) {
        $string = str_replace($item, $replaceTo, $string);
    }
    return $string;
}

function resumeIf($condition, $true, $false)
{
    return $condition ? $true : $false;
}
