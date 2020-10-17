<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\ItemListStatistic;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemListStatisticRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ItemListType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ItemListService extends AbstractService implements ServiceInterface
{
    /** @var object|User|null */
    private ?object $user;
    private UserItemRepository $userItemRepository;
    private ItemRepository $itemRepository;
    private ItemListStatisticRepository $statisticRepository;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $repository,
        ItemListStatisticRepository $statisticRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        CustomSerializer $serializer
    ) {
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository = $itemRepository;
        $this->statisticRepository = $statisticRepository;
        $token = $tokenStorage->getToken();
        $this->user = is_string($token->getUser()) ? null : $token->getUser();

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    public function assignListToUser(int $id)
    {
        /** @var ItemList $itemList */
        $itemList = $this->repository->find($id);

        $this->setStatistic($itemList, $this->user);

        $items = $this->itemRepository->findBy(['itemList' => $itemList]);
        /** @var Item $item */
        foreach ($items as $item) {
            $this->assignToUser($item);
        }
    }

    public function getItemListPrice(int $equipmentId): float
    {
        /** @var ItemList $itemList */
        $itemList = $this->repository->find($equipmentId);

        return $itemList->getPrice() - ($itemList->getPrice() * $itemList->getPromotion());
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new ItemList(), ItemListType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, ItemListType::class);
    }

    private function assignToUser(Item $item)
    {
        $userItem = $this->userItemRepository->findBy(['item' => $item, 'user' => $this->user]);

        $userItem = empty($userItem) ? new UserItem() : $userItem[0];

        $userItem->setName($item->getName());
        $userItem->setIcon($item->getIcon());
        $userItem->setCommand($item->getCommand());
        $userItem->setQuantity($userItem->getQuantity() + 1);
        $userItem->setItem($item);
        $userItem->setUser($this->user);

        $this->userItemRepository->insert($userItem);
    }

    public function setStatistic(ItemList $itemList, User $user)
    {
        $itemListStatistic = new ItemListStatistic();
        $itemListStatistic->setItemList($itemList);
        $itemListStatistic->setUser($user);

        $this->statisticRepository->insert($itemListStatistic);
    }
}
