<?php

date_default_timezone_set("America/Sao_Paulo");

const PDO_DEFAULT_FETCH_MODE = \PDO::FETCH_ASSOC;
const DEFAULT_TITLE = "New Framework";
const GENERAL_EXTENSION = "php";
const ROOT = __DIR__;
const DS = DIRECTORY_SEPARATOR;
const CONFIG_PATH = __DIR__ . "/../app/config/";
const DEBUG = true;
define("FRAMEWORK_INITIALIZE_TIME", time());
defined("CACHE_PATH") or define("CACHE_PATH", dirname(__DIR__, 2) . DS . "app" . DS . "Cache");
define("ROOT_PATH", dirname(dirname(__DIR__)));
define("BASE_PATH", dirname(dirname(__DIR__)));

const files_ext = [
    '.txt',
    '.php',
    '.css',
    '.js',
    '.phtml',
    '.html'
];

const
YEAR = 31104000,
MONTH = 2592000,
WEEK = 604800,
DAY = 86400,
HOUR = 3600,
MINUTE = 60,
SECONDS = 1;