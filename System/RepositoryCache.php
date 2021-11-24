<?php

namespace Core\System;

use Core\Repository\Interfaces\Repository;

class RepositoryCache
{

    private static array $repositories = [];

    public static function set($key, Repository $repository)
    {
        self::$repositories[$key] = $repository;
    }

    public static function get($key): Repository
    {
        return self::$repositories[$key];
    }

    public static function remove($key): bool
    {
        if (in_array($key, self::$repositories)) {
            unset(self::$repositories[$key]);
            return true;
        }
        return false;
    }

}