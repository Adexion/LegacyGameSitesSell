<?php

namespace MNGame\Service;

class EnvironmentService
{
    public const PROD = 'prod';
    public const DEV = 'dev';
    public const TEST = 'test';

    private string $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public function getEnvironment(): string
    {
        return $this->env;
    }

    public function isTest(): bool
    {
        return $this->getEnvironment() === self::TEST;
    }

    public function isProd(): bool
    {
        return $this->getEnvironment() === self::PROD;
    }

    public function isDev(): bool
    {
        return $this->getEnvironment() === self::DEV;
    }
}
