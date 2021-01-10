<?php

namespace ModernGame\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UnexpectedValueException;

class ServerProvider
{
    private array $serverList;
    private ?SessionInterface $session;
    private string $defaultQueryServerId;
    private string $defaultRCONServerId;

    public function __construct(ContainerInterface $container, SessionInterface $session)
    {
        $this->session = $session;
        $this->serverList = $container->getParameter('server');
        $this->defaultQueryServerId = $container->getParameter('defaultQueryServerId');
        $this->defaultRCONServerId = $container->getParameter('defaultRconServerId');
    }

    public function getServerData(int $serverId = null): array
    {
        if ($serverId !== null && !isset($this->serverList[$serverId])) {
            throw new UnexpectedValueException('Given server does not exist');
        }

        return $this->serverList[$serverId] ?? $this->serverList[$this->defaultRCONServerId];
    }

    public function getServerList(): array
    {
        return $this->serverList;
    }

    public function getSessionServer(): array
    {
        return $this->getServerData($this->session->get('serverId') ?? null);
}

    public function getDefaultQueryServerId(): string
    {
        return $this->defaultQueryServerId;
    }

    public function getDefaultRCONServerId(): string
    {
        return $this->defaultRCONServerId;
    }
}