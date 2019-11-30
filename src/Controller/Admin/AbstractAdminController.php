<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Service\ServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractAdminController extends AbstractController
{
    protected const REPOSITORY_CLASS = '';

    public function postEntity(Request $request, ServiceInterface $service): JsonResponse
    {
        /** @var object $entity */
        $entity = $service->mapEntity($request);

        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository($this::REPOSITORY_CLASS);
        $repository->insert($entity);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function getEntity(Request $request): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository($this::REPOSITORY_CLASS);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : [$repository->find($id)]);
    }

    public function putEntity(Request $request, ServiceInterface $service): JsonResponse
    {
        /** @var object $entity */
        $entity = $service->mapEntityById($request);

        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository($this::REPOSITORY_CLASS);
        $repository->update($entity);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function deleteEntity(Request $request): JsonResponse
    {
        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository($this::REPOSITORY_CLASS);
        $repository->delete($request->query->getInt('id'));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
