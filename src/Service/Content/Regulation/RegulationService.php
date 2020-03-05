<?php

namespace ModernGame\Service\Content\Regulation;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Database\Repository\RegulationRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\RegulationType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RegulationService extends AbstractService implements ServiceInterface
{
    private RegulationCategoryRepository $regulationCategoryRepository;
    private RegulationMapper $mapper;

    /** @var AbstractRepository|RegulationRepository  */
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

    public function getRules(): array
    {
        return $this->serializer->serialize(
            $this->mapper->mapRules(
                $this->repository->getRegulation()
            )
        )->toArray();
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request)
    {
        return $this->map($request, new Regulation(), RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }

    /**
     * @throws ContentException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, RegulationType::class, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
    }
}
