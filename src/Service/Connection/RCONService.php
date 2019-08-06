<?php

namespace ModernGame\Service\Connection;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\UserItemRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RCONService
{
    private const HOST = 's625567.csrv.pl';
    private const PORT = 9928;
    private const PASSWORD = '8da33335f300ab1d16a6';

    private $client;
    private $userItemRepository;

    /** @var User */
    private $user;

    public function __construct(UserItemRepository $userItemRepository, TokenStorageInterface $tokenStorage)
    {
        $this->client = new RconConnection(self::HOST, self::PORT, self::PASSWORD, 3);
        $this->client->connect();

        $this->userItemRepository = $userItemRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function getPlayerList()
    {
        $this->client->sendCommand('list');

        return (new PlayerListResponseMapper())->getResponse(
            $this->client->getResponse()
        );
    }

    public function executeItem(string $itemId = null)
    {
        /** @var UserItem[] $userItems */
        $userItems = empty($itemList)
            ? $this->userItemRepository->findBy(['userId' => $this->user->getId()])
            : [$this->userItemRepository->find($itemId)];

        foreach ($userItems as $item) {
            $this->client->sendCommand(sprintf($item->getCommand(), $this->user->getUsername()));

            $responseBuilder = new EquipmentCommandResponseMapper($this->user->getUsername());
            $response = $responseBuilder->getResponse(
                $this->client->getResponse()
            );

            $this->userItemRepository->deleteItem($item);
        }

        return $response ?? 'Nothing to execute';
    }
}
