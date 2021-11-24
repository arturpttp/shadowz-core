<?php

namespace Core\System;

use stdClass;

class Request
{

    public static stdClass $post;
    public static stdClass $get;
    public static stdClass $all;

    public static function start(): Request
    {

        self::$post = new stdClass();
        self::$get = new stdClass();
        self::$all = new stdClass();
        self::$all->get = new stdClass();
        self::$all->post = new stdClass();

        foreach ($_GET as $key => $value) {
            self::$get->$key = $value;
            self::$all->get->$key = $value;
        }

        foreach ($_POST as $key => $value) {
            self::$post->$key = $value;
            self::$all->post->$key = $value;
        }

        return new self();
    }

    public static function get(string $key): mixed {
        return self::$get->$key;
    }

    public static function post(string $key): mixed {
        return self::$post->$key;
    }

    public static function setGet(string $key, string $value) {
        $_GET[$key] = $value;
        self::$get->$key = $value;
    }

    public static function setPost(string $key, string $value) {
        $_POST[$key] = $value;
        self::$post->$key = $value;
    }

}