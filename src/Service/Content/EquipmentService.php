<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Equipment;
use ModernGame\Database\Entity\EquipmentItem;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\EquipmentItemRepository;
use ModernGame\Database\Repository\EquipmentRepository;
use ModernGame\Database\Repository\UserItemRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\EquipmentType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class EquipmentService
{
    private $userItemRepository;
    private $itemShopItemRepository;
    private $equipmentRepository;
    private $form;
    private $formErrorHandler;

    public function __construct(
        UserItemRepository $userItemRepository,
        EquipmentItemRepository $itemShopItemRepository,
        EquipmentRepository $equipmentRepository,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler
    ) {
        $this->userItemRepository = $userItemRepository;
        $this->itemShopItemRepository = $itemShopItemRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
    }

    public function assignEquipmentToUser(int $equipmentId, int $userId)
    {
        $itemList = $this->itemShopItemRepository->findBy(['equipmentId' => $equipmentId]);

        /** @var EquipmentItem $item */
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

        $this->equipmentRepository->increaseCounterOfBuying($equipmentId);
    }

    public function getEquipmentPrice(int $equipmentId): float
    {
        /** @var Equipment $equipment */
        $equipment = $this->equipmentRepository->find($equipmentId);

        return $equipment->getPrice() - ($equipment->getPrice() * $equipment->getPromotion());
    }

    /**
     * @throws ArrayException
     */
    public function getMappedEquipment(Request $request)
    {
        $equipment = new Equipment();
        $form = $this->form->create(EquipmentType::class, $equipment);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $equipment;
    }
}
