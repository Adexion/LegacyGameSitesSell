<?php

namespace MNGame\Service\Client;

use MNGame\Database\Entity\Server;

interface ClientInterface
{
    public function __construct(Server $server);

    public function connect(): bool;

    public function sendCommand(string $message): bool;

    public function getResponse(): string;

    public function disconnect(): bool;
}