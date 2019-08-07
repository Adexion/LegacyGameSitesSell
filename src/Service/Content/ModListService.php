<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\ModList;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ModListType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ModListService
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
    public function getMappedMod(Request $request)
    {
        $mod = new ModList();

        $form = $this->form->create(ModListType::class, $mod);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $mod;
    }
}
