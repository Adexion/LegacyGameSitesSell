<?php

namespace ModernGame\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use UnexpectedValueException;

class ServerProvider
{
    private array $serverList;
    private ?Request $request;
    private string $defaultQueryServerId;
    private string $defaultRCONServerId;

    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
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

    public function getCookiesServer(): array
    {
        return $this->getServerData($this->request->cookies->get('serverId') ?? null);
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