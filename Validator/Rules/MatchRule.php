<?php

namespace Core\Validator\Rules;

class MatchRule implements \Core\Validator\Rule
{

    private string $matchFiled, $matchValue;

    public function __construct(string $matchFiled, string $matchValue)
    {
        $this->matchFiled = $matchFiled;
        $this->matchValue = $matchValue;
    }

    public function check(string $field, string $value): bool {
        return $value === $this->matchValue;
    }

    public function getMessage(string $field): string {
        return "$field must be equals to $this->matchFiled";
    }
}