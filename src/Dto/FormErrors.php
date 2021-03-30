<?php

namespace MNGame\Dto;

class FormErrors
{
    private array $errors = [];

    public function addError(string $key, string $error)
    {
        $this->errors[$key] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
