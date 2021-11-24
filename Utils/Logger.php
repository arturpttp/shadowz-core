<?php

namespace Core\Utils;


abstract class Logger
{

    private const EMERGENCY = 100;
    private const ALERT = 200;
    private const CRITICAL = 300;
    private const ERROR = 400;
    private const WARNING = 500;
    private const NOTICE = 600;
    private const INFO = 700;
    private const DEBUG = 800;

    private static function getStringByLevel($level = 700): string
    {
        return match ($level) {
            self::EMERGENCY => "EMERGENCY",
            self::ALERT => "ALERT",
            self::CRITICAL => "CRITICAL",
            self::ERROR => "ERROR",
            self::WARNING => "WARNING",
            self::NOTICE => "NOTICE",
            self::DEBUG => "DEBUG",
            default => "INFO",
        };
    }


    public static function log($level, $message, $placeholders = [], $die = false)
    {
        $levelName = self::getStringByLevel($level);
        $replacers = [];
        foreach ($placeholders as $key => $value) {
            $replacers["{$key}"] = $value;
            $message = new Str($message);
            if ($message->contains("{$key}"))
                $message = $message->replace("{{$key}}", $value);
            $message = $message->get();
        }
        $time = false;
        try {
            $time = Time::now();
        } catch (\Exception $e) {
            $time = false;
        }
        $lMessage = "[$levelName] $time - $message";
        $logMessage = [
            "logLevel" => $levelName,
            "time" => $time->toDateTimeString(),
            "message" => $message,
            "logMessage" => $lMessage
        ];
//        $logMessage = "{\"logLevel\":\"$levelName\"  \"message\":\"[$levelName] $time - $message\"}";
        pre(json_encode($logMessage, JSON_PRETTY_PRINT));
        if ($die)
            die;
    }

    public static function emergency($message, $placeholders = [], $die = false)
    {
        self::log(self::EMERGENCY, $message, $placeholders, $die);
    }

    public static function alert($message, $placeholders = [], $die = false)
    {
        self::log(self::ALERT, $message, $placeholders, $die);
    }

    public static function critical($message, $placeholders = [], $die = false)
    {
        self::log(self::CRITICAL, $message, $placeholders, $die);
    }

    public static function error($message, $placeholders = [], $die = false)
    {
        self::log(self::ERROR, $message, $placeholders, $die);
    }

    public static function warning($message, $placeholders = [], $die = false)
    {
        self::log(self::WARNING, $message, $placeholders, $die);
    }

    public static function notice($message, $placeholders = [], $die = false)
    {
        self::log(self::NOTICE, $message, $placeholders, $die);
    }

    public static function info($message, $placeholders = [], $die = false)
    {
        self::log(self::INFO, $message, $placeholders, $die);
    }

    public static function debug($message, $placeholders = [], $die = false)
    {
        self::log(self::DEBUG, $message, $placeholders, $die);
    }

    static function getFilePath()
    {
        //LogFile
    }

}