<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ItemListType;
use ModernGame\Service\AbstractService;
use ModernGame\Service\Serializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ItemListService extends AbstractService implements ServiceInterface
{
    private $userItemRepository;
    private $itemShopItemRepository;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $repository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        Serializer $serializer
    ) {
        $this->userItemRepository = $userItemRepository;
        $this->itemShopItemRepository = $itemRepository;

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    public function assignListToUser(int $id, int $userId)
    {
        $itemList = $this->itemShopItemRepository->findBy(['equipmentId' => $id]);

        /** @var Item $item */
        foreach ($itemList as $item) {
            $userItem = $this->userItemRepository->findBy(['itemId' => $item->getId(), 'userId' => $userId]);

            $userItem = empty($userItem) ? new UserItem() : $userItem[0];

            $userItem->setName($item->getName());
            $userItem->setIconUrl($item->getIconUrl());
            $userItem->setCommand($item->getCommand());
            $userItem->setQuantity($userItem->getQuantity() + 1);
            $userItem->setItemId($item->getId());
            $userItem->setUserId($userId);

            $this->userItemRepository->addItem($userItem);
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
        $this->map($request, new ItemList(), ItemListType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, ItemListType::class);
    }
}
