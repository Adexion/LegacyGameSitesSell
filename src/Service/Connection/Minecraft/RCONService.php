<?php

namespace ModernGame\Service\Connection\Minecraft;

use Exception;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Content\ItemListService;
use ModernGame\Service\User\WalletService;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    public function getPlayerList(string $serverId = null)
    {
        $client = $this->connectionService->getClient($serverId);
        $client->sendCommand($this->container->getParameter('command')['list']);

        return $client->getResponse();
    }

    public function executeItem(?int $itemId, UserInterface $user, $break = false): array
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemId)
            ? $this->userItemRepository->findBy(['user' => $user])
            : [$this->userItemRepository->find($itemId)];
        $break = strstr($this->getPlayerList(), $user->getUsername()) === false;

        foreach ($userItems as $item) {
            for ($i = 0; $i < $item->getQuantity(); $i++) {
                try {
                    if ($break) {
                        throw new Exception();
                    }

                    $client = $this->connectionService->getClient($item->getItem()->getServerId());
                    $client->sendCommand(sprintf($item->getCommand(), $user->getUsername()));

                    $lastResponse = $client->getResponse();
                    $response[] = $client->getResponse();
                } catch (Exception $e) {
                    $lastResponse = $this->connectionService::PLAYER_NOT_FOUND;
                }

                if (strpos($lastResponse, $this->connectionService::PLAYER_NOT_FOUND) !== false) {
                    if ($break) {
                        throw new ContentException([
                            'message' => $lastResponse
                        ]);
                    }

                    continue;
                }

                $this->userItemRepository->deleteItem($item);
            }
        }

        return $response ?? [];
    }

    public function executeItemListInstant(float $amount, int $itemListId = null, UserInterface $user = null): array
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($itemListId);
        if (!$itemList) {
            $this->walletService->changeCash($amount, $user);
            $response[] = 'Dziękujemy za wsparcie serwera!';

            return $response;
        }

        /** @var User|UserInterface $user */
        if (!empty($itemList->getId()) && ($itemList->getPrice() - ($itemList->getPrice() * $itemList->getPromotion())) > $amount) {
            $this->walletService->changeCash($amount, $user);

            return ['Kwota zakupu jest mniejsza niż kwota zapłacona. Środki przypisano do portfela.'];
        }

        $this->itemListService->setStatistic($itemList, $user);
        $items = $this->itemRepository->findBy(['itemList' => $itemList]) ?? [];
        $break = strstr($this->getPlayerList(), $user->getUsername()) === false;

        foreach ($items ?? [] as $item) {
            for ($i = 0; $i < $item->getQuantity(); $i++) {
                try {
                    if ($break) {
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
                }
            }
        }

        $response[] = 'Dziękujemy za wsparcie serwera!';

        return $response;
    }
}
