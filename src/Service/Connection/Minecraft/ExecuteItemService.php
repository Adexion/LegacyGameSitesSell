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
use ModernGame\Exception\ItemListNotFoundException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\ServerProvider;
use ModernGame\Service\User\WalletService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ExecuteItemService
{
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListRepository $itemListRepository;
    private ServerConnectionService $connectionService;
    private WalletService $walletService;
    private ItemListService $itemListService;
    private ServerProvider $serverProvider;
    private RCONService $RCONService;
    private ContainerInterface $container;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        ServerConnectionService $connectionService,
        WalletService $walletService,
        ItemListService $itemListService,
        ServerProvider $serverProvider,
        RCONService $RCONService,
        ContainerInterface $container
    ) {
        $this->connectionService = $connectionService;
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository = $itemRepository;
        $this->itemListRepository = $itemListRepository;
        $this->walletService = $walletService;
        $this->itemListService = $itemListService;
        $this->serverProvider = $serverProvider;
        $this->RCONService = $RCONService;
        $this->container = $container;
    }

    /**
     * @throws ContentException
     */
    public function executeItem(UserInterface $user, ?int $itemId = null): int
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemId)
            ? $this->userItemRepository->findBy(['user' => $user])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems ?? [] as $userItem) {
            for ($i = 0; $i < $userItem->getQuantity(); $i++) {
                $server = $this->serverProvider->getServerData($userItem->getItem()->getItemList()->getServerId());
                $response = $this->request($userItem->getItem(), $user, $server);

                if (strpos($response, $server['playerNotFoundCommunicate']) !== false) {
                    return Response::HTTP_PARTIAL_CONTENT;
                }

                $this->userItemRepository->deleteItem($userItem);
            }
        }

        return Response::HTTP_OK;
    }

    /**
     * @throws ContentException
     * @throws ItemListNotFoundException
     * @throws PaymentProcessingException
     */
    public function executeItemListInstant(float $amount, int $itemListId = null, UserInterface $user = null, bool $isFromWallet = null): int {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId);
        $this->handleError($amount, $itemList, $user, $isFromWallet);

        /** @var User $user */
        $this->itemListService->setStatistic($itemList, $user);
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];

        foreach ($items ?? [] as $item) {
            $server = $this->serverProvider->getServerData($item->getItemList()->getServerId());
            $response = $this->request($item, $user, $server);

            if (strpos($response, $server['playerNotFoundCommunicate']) !== false) {
                $isSomeItemAssignedToEquipment = (bool)$this->userItemRepository->createItem($user, $item);
            }
        }

        if ($isSomeItemAssignedToEquipment ?? false) {
            return Response::HTTP_PARTIAL_CONTENT;
        }

        return Response::HTTP_OK;
    }

    private function isItemOnWhiteList(string $command): bool
    {
        $whiteListCommands = $this->container->getParameter('whitelistCommands');

        foreach ($whiteListCommands as $partialCommand) {
            if (strpos($command, $partialCommand) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws ContentException
     */
    private function request(Item $item, UserInterface $user, array $server): string
    {
        if (!$this->RCONService->isUserLogged($user, $server['id']) && !$this->isItemOnWhiteList($item->getCommand())) {
            return $server['playerNotFoundCommunicate'];
        }

        $client = $this->connectionService->getClient($server);
        $client->sendCommand(sprintf($item->getCommand(), $user->getUsername()));

        return $client->getResponse();
    }

    /**
     * @throws ItemListNotFoundException
     * @throws PaymentProcessingException
     * @throws ContentException
     */
    private function handleError(float $amount, ItemList $itemList = null, UserInterface $user = null, bool $isFromWallet = null)
    {
        if (!$itemList) {
            $this->walletService->changeCash($amount, $user);

            throw new ItemListNotFoundException();
        }

        /** @var User|UserInterface $user */
        if ($itemList->getAfterPromotionPrice() > $amount) {
            if (!$isFromWallet) {
                $this->walletService->changeCash($amount, $user);
            }

            throw new PaymentProcessingException();
        }
    }
}