<?php

namespace MNGame\Service\Connection\Client;

interface ClientInterface
{
    public function __construct(string $host, string $port, string $password, int $timeout = 10);

    public function connect(): bool;

    public function sendCommand(string $message): bool;

    public function getResponse(): string;

    public function disconnect(): bool;
}