<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Repository\RegulationCategoryRepository;
use ModernGame\Form\RegulationType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegulationService
{
    private $form;
    private $validator;
    private $formErrorHandler;
    private $regulationCategoryRepository;

    public function __construct(
        FormFactoryInterface $form,
        ValidatorInterface $validator,
        FormErrorHandler $formErrorHandler,
        RegulationCategoryRepository $regulationCategoryRepository
    ) {
        $this->form = $form;
        $this->validator = $validator;
        $this->formErrorHandler = $formErrorHandler;
        $this->regulationCategoryRepository = $regulationCategoryRepository;
    }

    public function getMappedRegulation(Request $request)
    {
        $regulation = new Regulation();

        $formRegulation = $this->form->create(RegulationType::class, $regulation, [
            'categories' => $this->regulationCategoryRepository->getCategoryList()
        ]);
        $this->formErrorHandler->handle($formRegulation);

        $formRegulation->handleRequest($request);

        return $regulation;
    }
}
