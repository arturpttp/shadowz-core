<?php

namespace Core\System;

use Core\Utils\Logger;

class Config
{

    private static array $configs = [];

    public static function load(): Config {
        $dirFiles = scandir(CONFIG_PATH);
        foreach ($dirFiles as $key => $value) {
            $ext = explode('.', $value)[1];
            if (in_array($ext, files_ext))
                $value = str_replace($ext, '', $value);
            $file = CONFIG_PATH . "$value";
            if ($value === '.' || $value === '..')
                continue;
            if (file_exists($file)) {
                $object = require_once $file;
                if (is_array($object)) {
                    self::create(replaceAll($value, files_ext), $object);
                }else {
                    Logger::error("File {file} doesn't return an array.", ["file" => $file]);
                }
            }
        }
        return new self;
    }

    public static function create(string $name, array $config) {
        self::$configs[$name] = $config;
    }

    public static function get(string $name) : array{
        return self::$configs[$name];
    }

}