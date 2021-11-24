<?php

namespace Core\System\Traits;

use Core\Utils\Session;

trait Url
{

    public static function redirect($url, $with = [], $time = 0, $message = false, $die = false)
    {
        if (count($with) > 0 && !empty($with))
            foreach ($with as $key => $value)
                Session::set($key, $value);
        echo "<meta http-equiv=\"refresh\" content=\"{$time}; URL='{$url}\"/>";
        if ($message)
            echo "<span class='alert alert-danger'>{$message}</span>";
        if ($die)
            die;
    }

}