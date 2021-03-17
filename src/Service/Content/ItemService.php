<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Item;
use ModernGame\Database\Repository\ItemListRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ItemType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
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
        CustomSerializer $serializer
    ) {
        parent::__construct($form, $formErrorHandler, $itemListRepository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request): Item
    {
        return $this->map($request, new Item(), ItemType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request): Item
    {
        return $this->mapById($request, ItemType::class);
    }
}
