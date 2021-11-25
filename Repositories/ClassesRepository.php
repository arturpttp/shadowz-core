<?php

namespace Core\Repositories;

class ClassesRepository extends \Core\Repository\AbstractRepository
{

    public array $classes;

    public function __construct(array $classes)
    {
        $this->classes = $classes;
        foreach ($classes as $name => $path) {
            $this->set($name, $path);
        }
        parent::__construct(new ClassesStore($this));
    }

    public function getRepositoryName(): string
    {
        return "classes";
    }

    public function getFileName(): string
    {
        return "classes";
    }
}