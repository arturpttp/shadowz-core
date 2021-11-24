<?php

namespace Core;

use Core\Repositories\ClassesRepository;
use Core\Repository\Interfaces\Factory;
use Core\Repository\Interfaces\Repository;
use Core\System\Config;
use Core\System\RepositoryCache;
use Core\Utils\Logger;

class Application implements Factory {

    private static ?Application $instance = null;
    private static array $classes;
    private static ClassesRepository $classesRepository;

    public function __construct() {
        self::$classes = [
            'database' => 'Core/Database',
            'cache' => 'Core/Repository',
            'validator' => 'Core/Validator'
        ];
        self::$classesRepository = new ClassesRepository(self::$classes);
    }

    public static function init(): void
    {
        if (self::$instance === null or !(self::$instance instanceof Application))
            self::$instance = new Application();
    }

    public static function getInstance(): Application
    {
        if (self::$instance === null or !(self::$instance instanceof Application))
            self::$instance = new Application();
        return self::$instance;
    }

    public function store($name = null): ?Repository
    {
        if (is_null($name)) return null;
        return RepositoryCache::get($name);
    }
}