<?php

namespace Core\Repository\Interfaces;

interface Factory
{

    public function store($name = null): ?Repository;

}