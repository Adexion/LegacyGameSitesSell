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

class ItemListService extends AbstractService implements ServiceInterface
{
    private $userItemRepository;
    private $itemRepository;
    private $statisticRepository;

    /**
     * @var User user
     */
    private $user;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $repository,
        ItemListStatisticRepository $statisticRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        Serializer $serializer
    ) {
        $this->userItemRepository = $userItemRepository;
        $this->itemRepository = $itemRepository;
        $this->statisticRepository = $statisticRepository;
        $this->user = $tokenStorage->getToken()->getUser();

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    public function assignListToUser(int $id)
    {
        /** @var ItemList $itemList */
        $itemList = $this->repository->find($id);

        $this->setStatistic($itemList);

        $items = $this->itemRepository->findBy(['itemList' => $itemList]);
        /** @var Item $item */
        foreach ($items as $item) {
            $this->assignToUser($item);
        }

        /** @var ItemListRepository $repository */
        $repository = $this->repository;
        $repository->increaseCounterOfBuying($id);
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
        $userItem->setIconUrl($item->getIconUrl());
        $userItem->setCommand($item->getCommand());
        $userItem->setQuantity($userItem->getQuantity() + 1);
        $userItem->setItem($item);
        $userItem->setUser($this->user);

        $this->userItemRepository->insert($userItem);
    }

    private function setStatistic(ItemList $itemList)
    {
        $itemListStatistic = new ItemListStatistic();
        $itemListStatistic->setItemList($itemList);
        $itemListStatistic->setUserId($this->user->getId());

        $this->statisticRepository->insert($itemListStatistic);
    }
}
