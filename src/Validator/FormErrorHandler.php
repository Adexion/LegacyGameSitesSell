<?php

namespace ModernGame\Validator;

use ModernGame\Dto\FormErrors;
use ModernGame\Exception\ArrayException;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormErrorHandler
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function handle(FormInterface $form)
    {
        $exception = new FormErrors();

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $fields = $this->getFieldPath($error->getOrigin());

                if (!empty($fields)) {
                    $exception->addError($fields, $error->getMessage());
                }
            }
        }

        if (!empty($exception->getErrors())) {
            throw new ArrayException($exception->getErrors());
        }
    }

    private function getFieldPath(FormInterface $form)
    {
        $fieldsName = array($form->getName());
        $parentForm = $form->getParent();

        while ($parentForm !== null) {
            $fieldsName[] = $parentForm->getName();
            $parentForm = $parentForm->getParent();
        }

        $fieldsName = array_reverse($fieldsName);

        $fieldPath = '';
        foreach ($fieldsName as $key => $field) {
            if ($key > 1) {
                $fieldPath .= '[' . $field . ']';
                break;
            }

            $fieldPath = $field;
        }

        return $fieldPath;
    }
}
