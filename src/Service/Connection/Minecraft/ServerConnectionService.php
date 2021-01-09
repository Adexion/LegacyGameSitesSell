<?php

namespace ModernGame\Service\Connection\Minecraft;

use ModernGame\Exception\ContentException;
use ModernGame\Service\EnvironmentService;
use ModernGame\Service\ServerProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServerConnectionService
{
    private const SEVER_NOT_RESPONDING = 'Nie można nawiązać połączenia';

    private EnvironmentService $environmentService;

    public function __construct(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }

    /** @throws ContentException */
    public function getClient(array $server): RCONConnection
    {
        $client = new RCONConnection($server['host'], $server['port'], $server['password'], $this->environmentService->isProd(), 5);
        @$client->connect();

        if (strpos($client->getResponse(), self::SEVER_NOT_RESPONDING) !== false) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }

        return $client;
    }
}
