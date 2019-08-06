<?php

namespace ModernGame\Dto;

class FormErrors
{
    private $errors = [];

    public function addError(string $key, string $error)
    {
        $this->errors[$key] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
