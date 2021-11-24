<?php

namespace Core\Validator\Rules;

use Core\Validator\Rule;

class Email implements Rule
{

    public function check(string $field, string $value): bool {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function getMessage(string $field): string {
        return "$field is not a valid email";
    }
}