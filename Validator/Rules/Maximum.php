<?php

namespace Core\Validator\Rules;

class Maximum implements \Core\Validator\Rule
{

    private int $maximum;

    public function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    public function check(string $field, string $value): bool
    {
        return !(strlen($value) > $this->maximum);
    }

    public function getMessage(string $field): string {
        return "$field must've a maximum of $this->maximum characters";
    }
}