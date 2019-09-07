<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ItemType;
use ModernGame\Service\AbstractService;
use ModernGame\Service\Serializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ItemService extends AbstractService implements ServiceInterface
{
    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        ItemListRepository $itemListRepository,
        Serializer $serializer
    ) {
        parent::__construct($form, $formErrorHandler, $itemListRepository, $serializer);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new Item(), ItemType::class);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, ItemType::class);
    }
}
