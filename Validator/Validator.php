<?php

namespace Core\Validator;

use Core\Utils\Session;

class Validator
{

    private ?array $errors = null;

    public function make(array $data): bool
    {
        foreach ($data as $field => $fieldData) {
            $value = $fieldData['value'];
            $rules = $fieldData['rules'];
            foreach ($rules as $rule)
                if ($rule instanceof Rule)
                    if (!$rule->check($field, $value))
                        $this->errors[$field] = $rule->getMessage($field);
        }
        if (!empty($this->errors) && count($this->errors)) {
            Session::set('errors', $this->errors);
            return false;
        }
        return true;
    }

}