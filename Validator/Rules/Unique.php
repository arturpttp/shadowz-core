<?php

namespace Core\Validator\Rules;

use Core\Bases\Model;

class Unique implements \Core\Validator\Rule
{

    private Model $model;
    private string $column;

    public function __construct(Model $model, string $column)
    {
        $this->model = $model;
        $this->column = $column;
    }


    public function check(string $field, string $value): bool
    {
        return $this->model->database->contains($this->column, $value);
    }

    public function getMessage(string $field): string
    {
        return "This $field already exists";
    }
}