<?php

namespace Core;

use Core\Repositories\ClassesRepository;
use Core\Repository\Interfaces\Factory;
use Core\Repository\Interfaces\Repository;
use Core\System\Config;
use Core\System\RepositoryCache;
use Core\System\Router;
use Core\System\User\User;
use Core\Utils\Logger;

class Application implements Factory {

    private static ?Application $instance = null;
    private static array $classes;
    private static ClassesRepository $classesRepository;
    private ?User $user;
    public ?Router $router = null;

    public function __construct() {
        self::$classes = [
            'database' => 'Core/Database',
            'cache' => 'Core/Repository',
            'validator' => 'Core/Validator',
            'user' => 'System/User'
        ];
        self::$classesRepository = new ClassesRepository(self::$classes);
        $this->setUser(null);
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

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}