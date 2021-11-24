<?php

namespace Core\Validator\Rules;

use Core\Validator\Rule;

class Required implements Rule
{

    public function check(string $field, string $value): bool {
        if (empty($value) || strlen($value) <= 0 || $field === '') {
            return false;
        }
        return true;
    }

    public function getMessage(string $field): string {
        return "The field $field is required";
    }
}