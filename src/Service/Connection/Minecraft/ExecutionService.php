<?php

namespace MNGame\Service\Connection\Minecraft;

use MinecraftServerStatus\MinecraftServerStatus;
use MNGame\Database\Entity\Server;
use MNGame\Exception\ContentException;
use MNGame\Service\Connection\Client\ClientFactory;
use MNGame\Service\ServerProvider;
use ReflectionException;
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

    public function getServerStatus(): ?array
    {
        $q = $this->serverProvider->getQuery();
        error_reporting(E_ALL & ~E_NOTICE);

        return MinecraftServerStatus::query($q['host'], $q['port']) ?: null;
    }

    /**
     * @throws ContentException
     * @throws ReflectionException
     */
    public function isUserLogged(UserInterface $user, Server $server): bool
    {
        $client = $this->clientFactory->create($server);
        $client->sendCommand(sprintf($server['userOnlineCommand'], $user->getUsername()));

        return filter_var($client->getResponse(), FILTER_VALIDATE_BOOLEAN);
    }
}
