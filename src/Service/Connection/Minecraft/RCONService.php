<?php

namespace ModernGame\Service\Connection\Minecraft;

use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\UserItemRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RCONService
{
    private $client;
    private $userItemRepository;
    private $user;
    private $container;

    public function __construct(
        UserItemRepository $userItemRepository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container
    ) {
        $this->container = $container;

        $serverData = $container->getParameter('minecraft');
        $this->client = new RconConnection($serverData['host'], $serverData['port'], $serverData['password']);
        $this->client->connect();

        $this->userItemRepository = $userItemRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPlayerList()
    {
        return $this->client->sendCommand($this->container->getParameter('command')['list']);
    }

    public function executeItem(string $itemId = null)
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemList)
            ? $this->userItemRepository->findBy(['userId' => $this->user->getId()])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems as $item) {
            $this->client->sendCommand(sprintf($item->getCommand(), $this->user->getUsername()));

            $response[] = $this->client->getResponse();

            $this->userItemRepository->deleteItem($item);
        }

        return $response ?? 'Nothing to execute';
    }
}
