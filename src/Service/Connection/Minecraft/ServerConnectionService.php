<?php

namespace ModernGame\Service\Connection\Minecraft;

use ModernGame\Service\EnvironmentService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServerConnectionService
{
    const PLAYER_NOT_FOUND = 'Nie znaleziono gracza.';

    private ContainerInterface $container;
    private EnvironmentService$environmentService;

    public function __construct(ContainerInterface $container, EnvironmentService $environmentService)
    {
        $this->container = $container;
        $this->environmentService = $environmentService;
    }

    public function getClient(string $serverId = null): RCONConnection
    {
        try {
            $serverData = $this->container->getParameter('minecraft');
            $client = new RCONConnection($serverData[$serverId]['host'], $serverData[$serverId]['port'],
                $serverData[$serverId]['password'], $this->environmentService->isProd(), 5);
            $client->connect();

            return $client;
        } catch (Exception $exception) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }
    }
}
