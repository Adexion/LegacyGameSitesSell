<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\ModList;
use ModernGame\Database\Repository\ModListRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ModListType;
use ModernGame\Service\AbstractService;
use ModernGame\Service\Serializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ModListService extends AbstractService implements ServiceInterface
{
    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        ModListRepository $repository,
        Serializer $serializer
    ) {
        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new ModList(), ModListType::class);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, ModListType::class);
    }
}
