<?php

namespace Core\Repository\Interfaces;

use Closure;

interface Repository extends CacheInterface
{

    public function pull($key, $default = null);
    public function put($key, $value = null);
    public function add($key, $value = null);
    public function increment($key, $value = 1);
    public function decrement($key, $value = 1);
    public function forever($key, $value = null);
    public function remember($key, Closure $callback);
    public function sear($key, Closure $callback);
    public function rememberForever($key, Closure $callback);
    public function forget($key);
    public function getStore(): Store;

    public function save();
    public function load();

    public function getRepositoryName(): string;
    public function getFileName(): string;
}