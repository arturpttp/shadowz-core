<?php

namespace Core\System;

use Core\Application;
use Core\System\Traits\Url;
use Core\System\User\User;
use Core\System\User\UserImpl;
use Core\Utils\Logger;

/**
 * @author ArturDev
 */
class Router
{

    /**
     * Default extension for php files
     *
     * @var string
     */
    private string $ext = '.php';
    private string $uri;

    public array $routes;
    public Application $application;

    public static ?array $staticRoutes = null;
    public static array $defaultInfos = [
        "auth" => false, //true if user need to be logged
        "level" => 0 //level to access the route User::$levels
    ];

    /**
     * @param array $routes
     * @param Application $application
     */
    public function __construct(array $routes, Application $application)
    {
        $this->routes = $this->nomalize($routes);
        $this->application = $application;
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    const CONTROLLER_DIR = __DIR__ . '/../../app/Controllers';

    private function nomalize($routes): array
    {
        $nRoutes = null;
        if (!is_null(self::$staticRoutes) && count(self::$staticRoutes) > 0)
            foreach (self::$staticRoutes as $staticRoute) {
                $nRoutes[] = $staticRoute;
            }
        foreach ($routes as $route) {
            $explode = explode("@", $route[0]);
            $controller = $explode[0];
            $method = $explode[1];

            if (isset($route[2])) {
                $r = [$explode[0], $explode[1], $route[1], $route[2], '@' => $route[0]];
            } else {
                $r = [$explode[0], $explode[1], $route[1], self::$defaultInfos, '@' => $route[0]];
            }
            $nRoutes[] = $r;
//            pre($r);
        }
        if ($nRoutes == null)
            Logger::error('routeNormalize->[{nRoutes}] is null', ['nRoutes' => $nRoutes]);
        return $nRoutes;
    }

    public function load()
    {
        foreach ($this->routes as $route) {
            $controller = $route[0];
            $method = $route[1];
            $authInfos = $route[3];
            $arroba = $route['@'];
            $urlArray = explode('/', $this->uri);
            $routeArray = explode('/', $route[2]);
            if ($authInfos['auth']) if (!UserImpl::$isLogged) {
                Url::redirect('/', ['errors' => ['You must be logged to access this page.']]);
                continue;
            } else if (!is_null(UserImpl::$logged) && $authInfos['level'] <= UserImpl::$logged->getLevel()) {
                Url::redirect('/', ['errors' => ['You don\'t have access to this page.']]);
                continue;
            }
            $params = [];
            for ($i = 0; $i < count($routeArray); $i++) {
                if ((str_contains($routeArray[$i], "{")) && (count($urlArray) == count($routeArray))) {
                    $routeArray[$i] = $urlArray[$i];
                    $params[] = $urlArray[$i];
                }
                $route[2] = implode('/', $routeArray);
            }
            $url = $route[2];
            if ($this->uri != $url)
                continue;
            if (file_exists(self::CONTROLLER_DIR . "/$controller$this->ext")) {
                $controller = "App\\Controllers\\$controller";
                if (class_exists($controller)) {
                    $controller = new $controller;
                    if (method_exists($controller, $method)) {
                        switch (count($params)) {
                            case 1:
                                $controller->$method($params[0], $this->getRequest());
                                break;
                            case 2:
                                $controller->$method($params[0], $params[1], $this->getRequest());
                                break;
                            case 3:
                                $controller->$method($params[0], $params[1], $params[2], $this->getRequest());
                                break;
                            case 4:
                                $controller->$method($params[0], $params[1], $params[2], $params[3], $this->getRequest());
                                break;
                            default:
                                $controller->$method($this->getRequest());
                                break;
                        }
                    } else
                        Logger::error(
                            'Method {method} doesn\'t exists in {controller}',
                            ['method' => $method, 'controller' => class_basename($controller)]
                        );
                }
            }
        }
    }

    /**
     * get request(post, get) for methods
     *
     * @return \stdClass
     */
    private function getRequest(): \stdClass
    {
        $obj = new \stdClass;
        $obj->get = new \stdClass();
        $obj->post = new \stdClass();
        foreach ($_GET as $key => $value) {
            $obj->get->$key = $value;
        }

        foreach ($_POST as $key => $value) {
            $obj->post->$key = $value;
        }

        return $obj;
    }

}