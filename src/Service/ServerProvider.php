<?php

namespace MNGame\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UnexpectedValueException;

class ServerProvider
{
    private const DEFAULT_SERVER_ID = 1;

    private $serverList;
    private ?SessionInterface $session;
    private $query;

    public function __construct(ContainerInterface $container, SessionInterface $session)
    {
        $this->session = $session;

        $this->query = $container->getParameter('query');
        $this->serverList = $container->getParameter('server');
    }

    public function getServer(int $serverId = null): array
    {
        if ($serverId !== null && !isset($this->serverList[$serverId])) {
            throw new UnexpectedValueException('Given server does not exist');
        }

        return $this->serverList[$serverId] ?? $this->serverList[self::DEFAULT_SERVER_ID];
    }

    public function getServerList(): array
    {
        return $this->serverList;
    }

    public function getSessionServer(): array
    {
        return $this->getServer($this->session->get('serverId') ?? null);
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getDefaultConnectionServerId(): int
    {
        return self::DEFAULT_SERVER_ID;
    }
}