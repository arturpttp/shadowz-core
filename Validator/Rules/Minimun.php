<?php

namespace Core\Validator\Rules;

class Minimun implements \Core\Validator\Rule
{

    private int $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public function check(string $field, string $value): bool
    {
        return !(strlen($value) < $this->minimum);
    }

    public function getMessage(string $field): string {
        return "$field needs at least $this->minimum characters";
    }
}