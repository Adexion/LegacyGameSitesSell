<?php

namespace MNGame\Dto;

class RuleDto
{
    private string $name;
    private array $rules;

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
