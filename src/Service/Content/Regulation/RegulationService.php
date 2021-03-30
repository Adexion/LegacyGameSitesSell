<?php

namespace MNGame\Service\Content\Regulation;

use MNGame\Database\Entity\Regulation;
use MNGame\Database\Repository\AbstractRepository;
use MNGame\Database\Repository\RegulationCategoryRepository;
use MNGame\Database\Repository\RegulationRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\RegulationType;
use MNGame\Serializer\CustomSerializer;
use MNGame\Service\AbstractService;
use MNGame\Service\ServiceInterface;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RegulationService extends AbstractService implements ServiceInterface
{
    private RegulationCategoryRepository $regulationCategoryRepository;
    private RegulationMapper $mapper;

    /** @var AbstractRepository|RegulationRepository */
    protected AbstractRepository $repository;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        RegulationCategoryRepository $regulationCategoryRepository,
        RegulationRepository $repository,
        RegulationMapper $mapper,
        CustomSerializer $serializer
    ) {
        $this->regulationCategoryRepository = $regulationCategoryRepository;
        $this->mapper = $mapper;

        parent::__construct($form, $formErrorHandler, $repository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request): Regulation
    {
        return $this->map($request, new Regulation(), RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request): Regulation
    {
        return $this->mapById($request, RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }
}
