<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\RegulationCategoryType;
use ModernGame\Service\AbstractService;
use ModernGame\Service\Serializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RegulationCategoryService extends AbstractService implements ServiceInterface
{
    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        RegulationCategoryRepository $repository,
        Serializer $serializer
    ) {
        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new RegulationCategory(), RegulationCategoryType::class);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, RegulationCategoryType::class);
    }
}
