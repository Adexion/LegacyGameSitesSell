<?php

namespace ModernGame\Service;

class EnvironmentService
{
    const PROD = 'prod';
    const DEV = 'dev';
    const TEST = 'test';

    private $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }

    public function getEnvironment()
    {
        return $this->env;
    }

    public function isTest()
    {
        return $this->getEnvironment() === self::TEST;
    }

    public function isProd()
    {
        return $this->getEnvironment() === self::PROD;
    }

    public function isDev()
    {
        return $this->getEnvironment() === self::DEV;
    }
}
