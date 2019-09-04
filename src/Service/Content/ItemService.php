<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ItemType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ItemService
{
    private $form;
    private $formErrorHandler;
    private $user;
    private $itemListRepository;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenStorageInterface $tokenStorage,
        ItemListRepository $itemListRepository
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->itemListRepository = $itemListRepository;
    }

    /**
     * @throws ArrayException
     */
    public function getMappedItem(Request $request)
    {
        $item = new Item();
        $form = $this->form->create(ItemType::class, $item, [
            'itemLists' => $this->itemListRepository->getAllList()
        ]);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $item;
    }
}
