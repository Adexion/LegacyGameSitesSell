<?php

namespace ModernGame\Service\Connection\Minecraft;

use MinecraftServerStatus\MinecraftServerStatus;
use ModernGame\Exception\ContentException;
use ModernGame\Service\ServerProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class RCONService
{
    private ServerProvider $serverProvider;
    private ServerConnectionService $connectionService;

    public function __construct(
        ServerProvider $serverProvider,
        ServerConnectionService $connectionService
    ) {
        $this->serverProvider = $serverProvider;
        $this->connectionService = $connectionService;
    }

    public function getServerStatus(string $serverId): ?array
    {
        $server = $this->serverProvider->getServerData($serverId);
        error_reporting(E_ALL & ~E_NOTICE);

        return MinecraftServerStatus::query($server['host'], $server['queryPort']) ?: null;
    }

    /**
     * @throws ContentException
     */
    public function isUserLogged(UserInterface $user, string $serverId): bool
    {
        return strstr($this->getPlayerList($serverId), $user->getUsername()) === false;
    }

    /**
     * @throws ContentException
     */
    public function getPlayerList(string $serverId = null): string
    {
        $server = $this->serverProvider->getServerData($serverId ?? $this->serverProvider->getDefaultRCONServerId());
        $client = $this->connectionService->getClient($server);

        $client->sendCommand($server['defaultCommand']);

        return $client->getResponse();
    }
}
