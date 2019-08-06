<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\EquipmentItem;
use ModernGame\Database\Repository\EquipmentRepository;
use ModernGame\Form\EquipmentItemType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EquipmentItemService
{
    private $form;
    private $formErrorHandler;
    private $user;
    private $equipmentRepository;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        EquipmentRepository $equipmentRepository
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->equipmentRepository = $equipmentRepository;
    }

    public function getMappedEquipmentItem(Request $request)
    {
        $equipmentItem = new EquipmentItem();
        $form = $this->form->create(EquipmentItemType::class, $equipmentItem, [
            'equipments' => $this->equipmentRepository->getEquipmentList()
        ]);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $equipmentItem;
    }
}
