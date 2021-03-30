<?php

namespace MNGame\Service\Content;

use MNGame\Database\Entity\Item;
use MNGame\Database\Repository\ItemListRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\ItemType;
use MNGame\Service\AbstractService;
use MNGame\Serializer\CustomSerializer;
use MNGame\Service\ServiceInterface;
use MNGame\Validator\FormErrorHandler;
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
