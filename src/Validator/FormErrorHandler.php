<?php

namespace MNGame\Validator;

use MNGame\Dto\FormErrors;
use MNGame\Exception\ContentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormErrorHandler
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @throws ContentException
     */
    public function handle(FormInterface $form)
    {
        $exception = new FormErrors();

        if (!$form->isSubmitted()) {
            $form->submit([]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $fields = $this->getFieldPath($error->getOrigin());

                if (!empty($fields)) {
                    $exception->addError($fields, $error->getMessage());
                } elseif (isset($fields)) {
                    $exception->addError('general', $error->getMessage());
                }
            }
        }

        if (!empty($exception->getErrors())) {
            throw new ContentException($exception->getErrors());
        }
    }

    private function getFieldPath(FormInterface $form): string
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
