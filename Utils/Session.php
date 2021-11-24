<?php

namespace Core\Utils;

class Session
{

    public static \stdClass $session;

    public static function start(): Session
    {
        if (!session_id()) session_start();

        self::$session = new \stdClass();

        foreach ($_SESSION as $key => $value) {
            self::$session->$key = $value;
        }
        return new self();
    }

    public static function get($key, $defaults = null): mixed
    {
        return self::has($key) ? $_SESSION[$key] : $defaults;
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
        self::$session->$key = $value;
    }

    public static function remove($key) {
        if (is_array($key))
            foreach ($key as $x)
                self::remove($x);
        else {
            unset($_SESSION[$key]);
            unset(self::$session->$key);
        }
    }

    public static function has($key): bool {
        $has = false;
        if (isset($_SESSION[$key]) && $_SESSION[$key] != null)
            $has = true;
        return $has;
    }



}