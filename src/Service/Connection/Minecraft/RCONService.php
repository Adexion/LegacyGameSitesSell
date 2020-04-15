<?php

namespace ModernGame\Service\Connection\Minecraft;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\EnvironmentService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RCONService
{
    /** @var UserInterface|User */
    private UserInterface $user;
    private RCONConnection $client;
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListRepository $itemListRepository;
    private ContainerInterface $container;

    /**
     * @throws ContentException
     */
    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        EnvironmentService $environmentService
    ) {
        $this->container = $container;

        try {
            $serverData = $container->getParameter('minecraft');
            $this->client = new RCONConnection($serverData['host'], $serverData['port'], $serverData['password'], $environmentService->isProd(), 5);
            $this->client->connect();
        } catch (Exception $exception) {
            throw new ContentException(['error' => 'Nie udało się połączyć z serwerem.']);
        }

        $this->userItemRepository = $userItemRepository;
        $this->itemRepository =  $itemRepository;
        $this->itemListRepository =  $itemListRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPlayerList()
    {
        $this->client->sendCommand($this->container->getParameter('command')['list']);

        return $this->client->getResponse();
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

    /**
     * @throws ContentException
     */
    public function executeItemListForDonation(float $amount, int $itemListId = null, string $username = null): array
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId) ?? new ItemList();

        if (!empty($itemList->getId()) && ($itemList->getPrice() - ($itemList->getPrice() * $itemList->getPromotion())) > $amount) {
            throw new ContentException(['error' => 'Kwota zakupu jest mniejsza niż kwota opłacenia']);
        }

        /** @var Item[] $items */
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];

        foreach ($items ?? [] as $item) {
            $this->client->sendCommand(sprintf($item->getCommand(), $username));

            $response[] = $this->client->getResponse();

            if (strpos($this->client->getResponse(), 'Nie znaleziono gracza.') !== false) {
                $userItem = new UserItem();

                $userItem->setUser($this->user);
                $userItem->setItem($item);
                $userItem->setQuantity(1);

                $this->userItemRepository->insert($userItem);
            }
        }

        $response[] = 'Dziękujemy za wsparcie serwera!';

        return $response;
    }
}
