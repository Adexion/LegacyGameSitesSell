<?php

namespace ModernGame\Service\Connection\Minecraft;

use Exception;
use MinecraftServerStatus\MinecraftServerStatus;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RCONService
{
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListRepository $itemListRepository;
    private ContainerInterface $container;
    private ServerConnectionService $connectionService;
    private WalletService $walletService;
    private UserProviderInterface $userProvider;
    private ItemListService $itemListService;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        ContainerInterface $container,
        ServerConnectionService $connectionService,
        WalletService $walletService,
        UserProviderInterface $userProvider,
        ItemListService $itemListService
    ) {
        $this->container = $container;

        $this->connectionService = $connectionService;
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository = $itemRepository;
        $this->itemListRepository = $itemListRepository;
        $this->walletService = $walletService;
        $this->userProvider = $userProvider;
        $this->itemListService = $itemListService;
    }

    public function getPlayerList(string $serverId = null): string
    {
        $client = $this->connectionService->getClient($serverId);
        $client->sendCommand($this->container->getParameter('command')['list']);

        return $client->getResponse();
    }

    public function getServerStatus(string $serverId = null): ?array
    {
        $serverData = $this->container->getParameter('minecraft');
        $server = $serverData[$serverId] ?? current($serverData);
        error_reporting(E_ALL & ~E_NOTICE);

        return MinecraftServerStatus::query($server['host'], $server['queryPort']) ?: null;
    }

    public function executeItem(?int $itemId, UserInterface $user): int
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemId)
            ? $this->userItemRepository->findBy(['user' => $user])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems as $item) {
            for ($i = 0; $i < $item->getQuantity(); $i++) {
                $isUserDisconnected = strstr($this->getPlayerList(), $user->getUsername()) === false;
                try {
                    if ($isUserDisconnected && !$this->isItemOnWhiteList($item->getCommand())) {
                        throw new Exception();
                    }

                    $client = $this->connectionService->getClient($item->getItem()->getServerId());
                    $client->sendCommand(sprintf($item->getCommand(), $user->getUsername()));

                    $lastResponse = $client->getResponse();
                } catch (Exception $e) {
                    $lastResponse = $this->connectionService::PLAYER_NOT_FOUND;
                }

                if (strpos($lastResponse, $this->connectionService::PLAYER_NOT_FOUND) !== false) {
                    return Response::HTTP_PARTIAL_CONTENT;
                }

                $this->userItemRepository->deleteItem($item);
            }
        }

        return Response::HTTP_OK;
    }

    public function executeItemListInstant(float $amount, int $itemListId = null, UserInterface $user = null, bool $isFromWallet = null): int
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId);
        if (!$itemList) {
            $this->walletService->changeCash($amount, $user);

            return Response::HTTP_OK;
        }

        /** @var User|UserInterface $user */
        if (!empty($itemList->getId()) && $itemList->getAfterPromotionPrice() > $amount) {
            if (!$isFromWallet) {
                $this->walletService->changeCash($amount, $user);
            }

            return Response::HTTP_PAYMENT_REQUIRED;
        }

        $this->itemListService->setStatistic($itemList, $user);
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];

        foreach ($items ?? [] as $item) {
            $isUserDisconnected = strstr($this->getPlayerList(), $user->getUsername()) === false;
            try {

                if ($isUserDisconnected && !$this->isItemOnWhiteList($item->getCommand())) {
                    throw new Exception();
                }

                $client = $this->connectionService->getClient($item->getServerId());
                $client->sendCommand(sprintf($item->getCommand(), $user->getUsername()));

                $lastResponse = $client->getResponse();
                if (strpos($lastResponse, $this->connectionService::PLAYER_NOT_FOUND) === false) {
                    $response[] = $client->getResponse();
                }
            } catch (Exception $e) {
                $lastResponse = $this->connectionService::PLAYER_NOT_FOUND;
            }

            if (strpos($lastResponse, $this->connectionService::PLAYER_NOT_FOUND) !== false) {
                $userItem = new UserItem();

                $userItem->setUser($user);
                $userItem->setItem($item);
                $userItem->setQuantity(1);
                $userItem->setName($item->getName());
                $userItem->setIcon($item->getIcon());
                $userItem->setCommand($item->getCommand());

                $this->userItemRepository->insert($userItem);
                $isSomItemAssignedToEquipment = true;
            }
        }

        if ($isSomItemAssignedToEquipment ?? false) {
            return Response::HTTP_PARTIAL_CONTENT;
        }

        return Response::HTTP_OK;
    }

    public function isItemOnWhiteList(string $command): bool
    {
        $whiteListCommands = $this->container->getParameter('whitelistCommands');

        foreach ($whiteListCommands as $partialCommand) {
            if (strpos($command, $partialCommand) !== false) {
                return true;
            }
        }

        return false;
    }
}
