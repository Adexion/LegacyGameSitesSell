<?php

namespace MNGame\Service\Content;

use MNGame\Database\Entity\RegulationCategory;
use MNGame\Database\Repository\RegulationCategoryRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\RegulationCategoryType;
use MNGame\Service\AbstractService;
use MNGame\Serializer\CustomSerializer;
use MNGame\Service\ServiceInterface;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RegulationCategoryService extends AbstractService implements ServiceInterface
{
    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        RegulationCategoryRepository $repository,
        CustomSerializer $serializer
    ) {
        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request): RegulationCategory
    {
        return $this->map($request, new RegulationCategory(), RegulationCategoryType::class);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request): RegulationCategory
    {
        return $this->mapById($request, RegulationCategoryType::class);
    }
}
