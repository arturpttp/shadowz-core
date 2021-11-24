<?php

namespace Core\Repository\Interfaces;

interface CacheInterface
{

    /**
     * Get item from the cache
     * @param $key | the unique key of item in the cache
     * @param mixed $defaults | value that returns when values doesn't exists in cache
     * @return mixed
     */
    public function get($key, $defaults = null);

    /**
     * Define a value in cache
     *
     * @param $key |
     * @param mixed $value |
     * @param int $time
     * @return mixed
     */
    public function set($key, $value = null, $time = 0);
    public function delete($key);
    public function clear($keep = []);
    public function getMultiple($keys, $default = null): array;
    public function setMultiple($values, $times = 0);
    public function deleteMultiple($keys);
    public function has($key): bool;
    public function all(): array;
}