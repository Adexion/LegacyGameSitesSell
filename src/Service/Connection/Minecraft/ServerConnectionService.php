<?php

namespace ModernGame\Service\Connection\Minecraft;

use ModernGame\Exception\ContentException;
use ModernGame\Service\EnvironmentService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServerConnectionService
{
    const PLAYER_NOT_FOUND = 'Player not found.';
    const SEVER_NOT_RESPONDING = 'Nie można nawiązać połączenia, ponieważ komputer docelowy aktywnie go odmawia.';

    private ContainerInterface $container;
    private EnvironmentService$environmentService;

    public function __construct(ContainerInterface $container, EnvironmentService $environmentService)
    {
        $this->container = $container;
        $this->environmentService = $environmentService;
    }

    public function getClient(string $serverId = null): RCONConnection
    {
        $serverData = $this->container->getParameter('minecraft');
        $server = $serverData[$serverId] ?? current($serverData);

        $client = new RCONConnection($server['host'], $server['port'],
            $server['password'], $this->environmentService->isProd(), 5);
        @$client->connect();

        if (strpos($client->getResponse(), self::SEVER_NOT_RESPONDING) !== false) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }

        return $client;
    }
}
