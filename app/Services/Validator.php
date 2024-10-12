<?php

namespace App\Services;

class Validator
{
    protected $errors = [];

    public function validate(array $rules, array $data)
    {
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                if (method_exists($this, $rule)) {
                    $this->{$rule}($field, $data[$field] ?? null);
                }
            }
        }

        return $this->errors;
    }

    protected function required($field, $value)
    {
        if (empty($value)) {
            $this->errors[$field] = ucfirst($field) . ' is required.';
        }
    }

    protected function email($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ucfirst($field) . ' must be a valid email address.';
        }
    }

    protected function min($field, $value, $minLength = 6)
    {
        if (strlen($value) < $minLength) {
            $this->errors[$field] = ucfirst($field) . " must be at least $minLength characters long.";
        }
    }

    // Add more validation rules as necessary
}
