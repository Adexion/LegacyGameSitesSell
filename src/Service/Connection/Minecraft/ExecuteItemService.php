<?php

namespace MNGame\Service\Connection\Minecraft;

use MNGame\Database\Entity\Item;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Server;
use MNGame\Database\Entity\User;
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Database\Repository\ItemRepository;
use MNGame\Database\Repository\UserItemRepository;
use MNGame\Exception\ContentException;
use MNGame\Exception\ItemListNotFoundException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\Connection\Client\ClientFactory;
use MNGame\Service\Content\ItemListService;
use MNGame\Service\Content\Parameter\ParameterProvider;
use MNGame\Service\ServerProvider;
use MNGame\Service\User\WalletService;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ExecuteItemService
{
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListRepository $itemListRepository;
    private ItemListService $itemListService;
    private ServerProvider $serverProvider;
    private ExecutionService $executionService;
    private ParameterProvider $container;
    private ClientFactory $clientFactory;
    private WalletService $walletService;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        ClientFactory $clientFactory,
        WalletService $walletService,
        ItemListService $itemListService,
        ServerProvider $serverProvider,
        ExecutionService $executionService,
        ParameterProvider $container
    ) {
        $this->clientFactory = $clientFactory;
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository = $itemRepository;
        $this->itemListRepository = $itemListRepository;
        $this->walletService = $walletService;
        $this->itemListService = $itemListService;
        $this->serverProvider = $serverProvider;
        $this->executionService = $executionService;
        $this->container = $container;
    }

    /**
     * @throws ContentException
     * @throws ReflectionException
     */
    public function executeItem(UserInterface $user, ?int $itemId = null): int
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemId)
            ? $this->userItemRepository->findBy(['user' => $user])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems ?? [] as $userItem) {
            for ($i = 0; $i < $userItem->getQuantity(); $i++) {
                $server = $this->serverProvider->getServer($userItem->getItem()->getItemList()->getServerId());
                $response = $this->request($userItem->getItem(), $user, $server);

                if (strpos($response, $server->getPlayerNotFoundCommunicate()) !== false) {
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
     * @throws ReflectionException
     */
    public function executeItemListInstant(float $amount, int $itemListId = null, UserInterface $user = null, bool $isFromWallet = null): int {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId);
        $this->handleError($amount, $itemList, $user, $isFromWallet);

        /** @var User $user */
        $this->itemListService->setStatistic($itemList, $user);
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];

        foreach ($items ?? [] as $item) {
            $server = $this->serverProvider->getServer($item->getItemList()->getServerId());
            $response = $this->request($item, $user, $server);

            if (strpos($response, $server->getPlayerNotFoundCommunicate()) !== false) {
                $isSomeItemAssignedToEquipment = (bool)$this->userItemRepository->createItem($user, $item);
            }
        }

        $this->walletService->changeCash(-$itemList->getAfterPromotionPrice(), $user);
        if ($isSomeItemAssignedToEquipment ?? false) {
            return Response::HTTP_PARTIAL_CONTENT;
        }

        return Response::HTTP_OK;
    }

    private function isItemOnWhiteList(string $command): bool
    {
        $executeImmediatelyCommands = $this->container->getParameter('executeImmediatelyCommands');

        foreach ($executeImmediatelyCommands as $partialCommand) {
            if (strpos($command, $partialCommand) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws ContentException
     * @throws ReflectionException
     */
    private function request(Item $item, UserInterface $user, Server $server): string
    {
        if (!$this->executionService->isUserLogged($user, $server) && !$this->isItemOnWhiteList($item->getCommand())) {
            return $server->getPlayerNotFoundCommunicate();
        }

        $client = $this->clientFactory->create($server);
        $client->sendCommand(sprintf($item->getCommand(), $user->getUsername()));

        $response = $client->getResponse();
        if (!$response) {
            return $server->getPlayerNotFoundCommunicate();
        }

        return $client->getResponse();
    }

    /**
     * @throws ItemListNotFoundException
     * @throws PaymentProcessingException
     * @throws ContentException
     */
    private function handleError(float $amount, ItemList $itemList = null, UserInterface $user = null, bool $isFromWallet = null) {
        if (!$itemList && !$isFromWallet) {
            $this->walletService->changeCash($amount, $user);

            throw new ItemListNotFoundException();
        } elseif (!$itemList && $isFromWallet) {
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