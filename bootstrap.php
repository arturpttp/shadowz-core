<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Core\Application;
use Core\System\Config;
use Core\System\Request;
use Core\System\Router;
use Core\Utils\Session;

include_once __DIR__."/functions.php";
include_once __DIR__."/config.php";

#Starting Configs
$config = Config::load();
#Starting Sessions
$session = Session::start();
#Starting Request
$request = Request::start();

#Starting Application
$application = new Application();
$application->init();

#Routing
$routes = require_once __DIR__ . '/../app/routes.php';
$router = new Router($routes, $application);
$router->load();