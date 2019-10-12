<?php

namespace ModernGame\Service\Connection\Minecraft;

use ErrorException;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RCONService
{
    private $client;
    private $user;
    private $userItemRepository;
    private $itemRepository;
    private $itemListRepository;
    private $container;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container
    ) {
        $this->container = $container;

        try {
            $serverData = $container->getParameter('minecraft');
            $this->client = new RconConnection($serverData['host'], $serverData['port'], $serverData['password'], 5);
            $this->client->connect();
        } catch (ErrorException $exception) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }

        $this->userItemRepository = $userItemRepository;
        $this->itemRepository =  $itemRepository;
        $this->itemListRepository =  $itemListRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPlayerList()
    {
        return $this->client->sendCommand($this->container->getParameter('command')['list']);
    }

    public function executeItem(int $itemId = null): array
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

        return $response ?? [];
    }

    public function executeItemList(float $amount, int $itemListId, string $username): array
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId);

        if ($itemList->getPrice() < $amount) {
            throw new ContentException(['error' => 'Kwota zakupu jest mniejsza niż kwota opłacenia']);
        }

        /** @var Item[] $items */
        $items = $this->itemRepository->findBy(['itemList' => $itemList]);

        foreach ($items as $item) {
            $this->client->sendCommand(sprintf($item->getCommand(), $username));

            $response[] = $this->client->getResponse();
        }

        return $response ?? [];
    }
}
