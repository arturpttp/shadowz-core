<?php

namespace Core\Utils;

class Str
{

    private string $raw;
    private string $string;
    private array $replacers;


    public function __construct($string, $replacers = [])
    {
        $this->raw = $string;
        $this->string = $string;
        $this->replacers = $replacers;
        $this->startup();
    }

    private function startup()
    {
        if (count($this->replacers) > 0)
            foreach ($this->replacers as $key => $value)
                if ($this->contains($key))
                    $this->replace($key, $value);
    }

    public function contains($key, $raw = false)
    {
        return strpos($raw ? $this->raw : $this->string, $key);
    }

    public function replace($key, $value)
    {
        $this->string = str_replace($key, $value, $this->string);
        return $this;
    }

    public function length($raw = false)
    {
        return count($raw ? $this->raw : $this->string);
    }

    public function print($raw = false)
    {
        echo $raw ? $this->raw : $this->string;
        return $this;
    }

    public function get()
    {
        return $this->string;
    }

    public function getRaw()
    {
        return $this->raw;
    }

}