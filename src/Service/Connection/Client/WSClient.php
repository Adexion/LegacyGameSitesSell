<?php

namespace MNGame\Service\Connection\Client;

use Throwable;
use WebSocket\Client;

class WSClient implements ClientInterface
{
    private string $host;
    private string $port;
    private string $password;
    private int $timeout;
    private Client $client;

    public function __construct(string $host, string $port, string $password, int $timeout = 10)
    {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->timeout = $timeout;
    }

    public function connect(): bool
    {
        $this->client = new Client('ws://' . $this->host . ':' . $this->port . '/');
        try { $this->client->send(''); } catch (Throwable $e) {}

        return $this->client->isConnected();
    }

    public function sendCommand(string $message): bool
    {
        if (!$this->client->isConnected()) {
            return false;
        }

        $this->client->text(json_encode([
            'header' => [
                'password' => $this->password
            ],
            'type' => 'execute',
            'message' => $message,
            'player' => 'CONSOLE',
        ]));

        return true;
    }

    public function getResponse(): string
    {
        try {
            return $this->getResponseBeautify(
                json_decode($this->client->receive(), true)['message'] ?? ''
            );
        } catch (Throwable $e) {}

        return false;
    }

    public function disconnect(): bool
    {
        $this->client->close();
        $this->client->__destruct();
        unset($this->client);

        return true;
    }

    private function getResponseBeautify(string $response): string
    {
        $removedFirstTag = preg_replace('/\[(.*)]\s/', '', $response);
        $removedColoredSign = preg_replace('/§(.?)/', '', $removedFirstTag);

        return preg_replace('/([^a-zA-Z0-9\/\r\n\s])/', '', $removedColoredSign);
    }
}