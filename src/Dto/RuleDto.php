<?php

namespace ModernGame\Dto;

class RuleDto
{
    private $name;
    private $rules;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function addRule(string $rule)
    {
        $this->rules[] = ['description' => $rule];
    }
}
