<?php

namespace Core\Validator;

interface Rule
{

    public function check(string $field, string $value): bool;

    public function getMessage(string $field): string;

}