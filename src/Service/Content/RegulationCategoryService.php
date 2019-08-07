<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\RegulationCategory;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\RegulationCategoryType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegulationCategoryService
{
    private $form;
    private $validator;
    private $formErrorHandler;

    public function __construct(
        FormFactoryInterface $form,
        ValidatorInterface $validator,
        FormErrorHandler $formErrorHandler
    ) {
        $this->form = $form;
        $this->validator = $validator;
        $this->formErrorHandler = $formErrorHandler;
    }

    /**
     * @throws ArrayException
     */
    public function getMappedRegulationCategory(Request $request)
    {
        $regulationCategory = new RegulationCategory();

        $formRegulationCategory = $this->form->create(RegulationCategoryType::class, $regulationCategory);
        $this->formErrorHandler->handle($formRegulationCategory);

        $formRegulationCategory->handleRequest($request);

        return $regulationCategory;
    }
}
