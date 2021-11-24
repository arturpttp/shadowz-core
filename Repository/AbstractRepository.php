<?php

namespace Core\Repository;

use Core\Repository\Interfaces\Repository;
use Core\Repository\Interfaces\Store;
use Closure;
use Core\System\RepositoryCache;
use Core\Utils\Logger;
use Core\Utils\Str;

abstract class AbstractRepository extends AbstractCache implements Repository
{

    private ?Store $store = null;

    public function __construct(AbstractStore $store = null, $load = true)
    {
        $this->store = $store;
        if ($store == null)
            if ($load) {
                $this->save();
                $this->load();
            }
            Logger::error("[{$store}] is undefined on class " . get_class($this));
        $this->set("store", get_class($store));
        RepositoryCache::set($this->getRepositoryName(), clone($this));
    }

    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->delete($key);
        return $value;
    }

    public function put($key, $value = null)
    {
        $this->set($key, $value);
    }

    public function add($key, $value = null)
    {
        if (!$this->has($key))
            $this->set($key, $value);
    }

    public function increment($key, $value = 1)
    {
        $initial = $this->pull($key, 0);
        $newValue = $initial + $value;
        $this->set($key, $newValue);
    }

    public function decrement($key, $value = 1)
    {
        $initial = $this->pull($key, 0);
        $newValue = $initial - $value;
        $this->set($key, $newValue);
    }

    public function forever($key, $value = null)
    {
        $this->set($key, $value);
    }

    public function remember($key, Closure $callback)
    {
        if ($this->has($key))
            return $this->get($key);
        $value = $callback();
        $this->set($key, $value);
        return $value;
    }

    public function sear($key, Closure $callback)
    {
        if ($this->has($key))
            return $this->get($key);
        $value = $callback();
        $this->forever($key, $value);
        return $value;
    }

    public function rememberForever($key, Closure $callback)
    {
        if ($this->has($key))
            return $this->get($key);
        $value = $callback();
        $this->forever($key, $value);
        return $value;
    }

    public function forget($key)
    {
        $this->delete($key);
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function save()
    {
        $jsonString = json_encode($this->items);
        $fileName = resumeIf(empty($this->getFileName()) || $this->getFileName() == "", $this->store->getPrefix(), $this->getFileName());
        $fileName = resumeIf((new Str($fileName))->contains(".json"), (new Str($fileName))->replace(".json", ""), $fileName);
        $file = fopen(CACHE_PATH . DS . "{$fileName}.json", "w+", false);
        fwrite($file, $jsonString);
    }

    public function load()
    {
        $fileName = resumeIf(empty($this->getFileName()) || $this->getFileName() == "", $this->store->getPrefix(), $this->getFileName());
        $fileName = resumeIf((new Str($fileName))->contains(".json"), (new Str($fileName))->replace(".json", ""), $fileName);
        $filePath = CACHE_PATH . DS . "{$fileName}.json";
        $obj = json_decode(file_get_contents($filePath));
        $items = [];
        foreach ($obj as $key => $value)
            $items[$key] = $value;
        $this->items = $items;
        $storeClass = $this->get("store");
        $this->store = new $storeClass($this);
    }

}