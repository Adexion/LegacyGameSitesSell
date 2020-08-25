<?php

namespace ModernGame\Service\Connection\Minecraft;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RCONService
{
    /** @var UserInterface|User */
    private UserInterface $user;
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListRepository $itemListRepository;
    private ContainerInterface $container;
    private ServerConnectionService $connectionService;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        ServerConnectionService $connectionService
    ) {
        $this->container = $container;

        $this->connectionService = $connectionService;
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository =  $itemRepository;
        $this->itemListRepository =  $itemListRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPlayerList(string $serverId = null)
    {
        $client = $this->connectionService->getClient($serverId);
        $client->sendCommand($this->container->getParameter('command')['list']);

        return $client->getResponse();
    }

    public function executeItem(int $itemId = null, $break = false): array
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemId)
            ? $this->userItemRepository->findBy(['user' => $this->user])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems as $item) {
            $client = $this->connectionService->getClient($item->getItem()->getServerId());
            $client->sendCommand(sprintf($item->getCommand(), $this->user->getUsername()));

            $response[] = $client->getResponse();
            if (strpos($client->getResponse(), 'Nie znaleziono gracza.') !== false) {
                if ($break) {
                    throw new ContentException(array(
                        'message' => $client->getResponse()
                    ));
                }

                continue;
            }

            $this->userItemRepository->deleteItem($item);
        }

        return $response ?? [];
    }

    /**
     * @param float $amount
     * @param int|null $itemListId
     * @param string|null $username
     * @return array
     *
     * @throws ContentException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function executeItemListInstant(float $amount, int $itemListId = null, string $username = null): array
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId) ?? new ItemList();

        if (!empty($itemList->getId()) && ($itemList->getPrice() - ($itemList->getPrice() * $itemList->getPromotion())) > $amount) {
            throw new ContentException(['error' => 'Kwota zakupu jest mniejsza niż kwota opłacenia']);
        }

        $itemList->setHowManyBuyers($itemList->getHowManyBuyers() + 1);
        $this->itemListRepository->update($itemList);

        /** @var Item[] $items */
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];

        foreach ($items ?? [] as $item) {
            $client = $this->connectionService->getClient($item->getServerId());
            $client->sendCommand(sprintf($item->getCommand(), $username));

            $response[] = $client->getResponse();

            if (strpos($client->getResponse(), $this->connectionService::PLAYER_NOT_FOUND) !== false) {
                $userItem = new UserItem();

                $userItem->setUser($this->user);
                $userItem->setItem($item);
                $userItem->setQuantity(1);
                $userItem->setName($item->getName());
                $userItem->setIcon($item->getIcon());
                $userItem->setCommand($item->getCommand());

                $this->userItemRepository->insert($userItem);
            }
        }

        $response[] = 'Dziękujemy za wsparcie serwera!';

        return $response;
    }
}
