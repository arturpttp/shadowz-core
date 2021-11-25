<?php

namespace Core\Repositories;

use Core\Repository\AbstractStore;

class ClassesStore extends AbstractStore {

    public function getPrefix(): string {
        return "";
    }
}