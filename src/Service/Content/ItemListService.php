<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\ItemList;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\ItemRepository;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ItemListType;
use ModernGame\Service\Serializer;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ItemListService
{
    private $userItemRepository;
    private $itemShopItemRepository;
    private $itemListRepository;
    private $form;
    private $formErrorHandler;
    private $serializer;

    public function __construct(
        UserItemRepository $userItemRepository,
        ItemRepository $itemRepository,
        ItemListRepository $itemListRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        Serializer $serializer
    ) {
        $this->userItemRepository = $userItemRepository;
        $this->itemShopItemRepository = $itemRepository;
        $this->itemListRepository = $itemListRepository;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->serializer = $serializer;
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

        $this->itemListRepository->increaseCounterOfBuying($id);
    }

    public function getItemListPrice(int $equipmentId): float
    {
        /** @var ItemList $itemList */
        $itemList = $this->itemListRepository->find($equipmentId);

        return $itemList->getPrice() - ($itemList->getPrice() * $itemList->getPromotion());
    }

    /**
     * @throws ArrayException
     */
    public function mapItemList(Request $request)
    {
        $itemList = new ItemList();
        $form = $this->form->create(ItemListType::class, $itemList);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $itemList;
    }

    /**
     * @throws ArrayException
     */
    public function mapItemListById(Request $request)
    {
        $list = $this->itemListRepository->find($request->request->getInt('id'));

        if (empty($list)) {
            throw new ArrayException(['id' => 'Ta wartość jest nieprawidłowa.']);
        }

        $form = $this->form->create(ItemListType::class, $list, ['method' => 'PUT']);

        $request->request->replace(
            $this->serializer->mergeDataWithEntity($list, $request->request->all())
        );

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $list;
    }
}
