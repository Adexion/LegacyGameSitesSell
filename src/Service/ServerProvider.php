<?php

namespace MNGame\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UnexpectedValueException;

class ServerProvider
{
    private $serverList;
    private ?SessionInterface $session;
    private $defaultQueryServerId;
    private $defaultConnectionServerId;

    public function __construct(ContainerInterface $container, SessionInterface $session)
    {
        $this->session = $session;
        $this->serverList = $container->getParameter('server');
        $this->defaultQueryServerId = $container->getParameter('defaultQueryServerId');
        $this->defaultConnectionServerId = $container->getParameter('defaultConnectionServerId');
    }

    public function getServer(int $serverId = null): array
    {
        if ($serverId !== null && !isset($this->serverList[$serverId])) {
            throw new UnexpectedValueException('Given server does not exist');
        }

        return $this->serverList[$serverId] ?? $this->serverList[$this->defaultConnectionServerId];
    }

    public function getServerList(): array
    {
        return $this->serverList;
    }

    public function getSessionServer(): array
    {
        return $this->getServer($this->session->get('serverId') ?? null);
    }

    public function getDefaultQueryServerId(): string
    {
        return $this->defaultQueryServerId;
    }

    public function getDefaultConnectionServerId(): string
    {
        return $this->defaultConnectionServerId;
    }
}