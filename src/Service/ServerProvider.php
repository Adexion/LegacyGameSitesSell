<?php

namespace MNGame\Service;

use MNGame\Database\Entity\Server;
use MNGame\Database\Repository\ServerRepository;
use MNGame\Service\Content\Parameter\ParameterProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UnexpectedValueException;

class ServerProvider
{
    private const DEFAULT_SERVER_ID = 0;

    private array $serverList;
    private ?SessionInterface $session;
    private $query;

    public function __construct(ParameterProvider $container, SessionInterface $session, ServerRepository $serverRepository)
    {
        $this->session = $session;

        $this->query = $container->getParameter('query');
        $this->serverList = $serverRepository->findAll();
    }

    public function getServer(int $serverId = null): Server
    {
        if ($serverId !== null && !isset($this->serverList[$serverId - 1])) {
            throw new UnexpectedValueException('Given server does not exist');
        }

        return $this->serverList[$serverId] ?? $this->serverList[self::DEFAULT_SERVER_ID];
    }

    public function getServerList(): array
    {
        return $this->serverList;
    }

    public function getSessionServer(): Server
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