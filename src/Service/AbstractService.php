<?php

namespace ModernGame\Service;

use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractService
{
    protected $form;
    protected $formErrorHandler;
    protected $repository;
    protected $serializer;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        AbstractRepository $repository,
        Serializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @throws ArrayException
     */
    protected function map(Request $request, $entity, string $formType, array $option = []) {
        $form = $this->form->create($formType, $entity, $option);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $entity;
    }

    /**
     * @throws ArrayException
     */
    protected function mapById(Request $request, string $formType, array $options = [])
    {
        $entity = $this->repository->find($request->request->getInt('id'));

        if (empty($list)) {
            throw new ArrayException(['id' => 'Ta wartość jest nieprawidłowa.']);
        }

        $form = $this->form->create($formType, $list, array_merge(['method' => 'PUT'], $options));

        $request->request->replace(
            $this->serializer->mergeDataWithEntity($list, $request->request->all())
        );

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $entity;
    }
}
