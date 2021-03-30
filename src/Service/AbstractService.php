<?php

namespace MNGame\Service;

use MNGame\Database\Repository\AbstractRepository;
use MNGame\Exception\ContentException;
use MNGame\Serializer\CustomSerializer;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractService
{
    protected FormFactoryInterface $form;
    protected FormErrorHandler $formErrorHandler;
    protected AbstractRepository $repository;
    protected CustomSerializer $serializer;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        AbstractRepository $repository,
        CustomSerializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @throws ContentException
     */
    protected function map(Request $request, $entity, string $formType, array $option = []) {
        $form = $this->form->create($formType, $entity, $option);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $entity;
    }

    /**
     * @throws ContentException
     */
    protected function mapById(Request $request, string $formType, array $options = [])
    {
        $entity = $this->repository->find($request->request->getInt('id'));

        if (empty($entity)) {
            throw new ContentException(['id' => 'Ta wartość jest nieprawidłowa.']);
        }

        $form = $this->form->create($formType, $entity, array_merge(['method' => 'PUT'], $options));

        $data = $this->serializer->mergeDataWithEntity($entity, $request->request->all());

        //HotFix for sending data by body
        $request->query->replace($data);
        $request->request->replace($data);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $entity;
    }
}
