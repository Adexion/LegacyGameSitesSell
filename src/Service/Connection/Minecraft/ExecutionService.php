<?php

namespace ModernGame\Service\Connection\Minecraft;

use MinecraftServerStatus\MinecraftServerStatus;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Client\ClientFactory;
use ModernGame\Service\ServerProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class ExecutionService
{
    private ServerProvider $serverProvider;
    private ClientFactory $clientFactory;

    public function __construct(
        ServerProvider $serverProvider,
        ClientFactory $clientFactory
    ) {
        $this->serverProvider = $serverProvider;
        $this->clientFactory = $clientFactory;
    }

    public function getServerStatus(string $serverId): ?array
    {
        $server = $this->serverProvider->getServer($serverId);
        error_reporting(E_ALL & ~E_NOTICE);

        return MinecraftServerStatus::query($server['host'], $server['queryPort']) ?: null;
    }

    /**
     * @throws ContentException
     */
    public function isUserLogged(UserInterface $user, string $serverId): bool
    {
        return strstr($this->getPlayerList($serverId), $user->getUsername()) !== false;
    }

    /**
     * @throws ContentException
     */
    public function getPlayerList(string $serverId = null): string
    {
        $server = $this->serverProvider->getServer($serverId ?? $this->serverProvider->getDefaultConnectionServerId());
        $client = $this->clientFactory->create($server);
        $client->sendCommand($server['defaultCommand']);

        return $client->getResponse();
    }
}
