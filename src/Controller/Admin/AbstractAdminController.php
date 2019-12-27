<?php

namespace ModernGame\Controller\Admin;

use Exception;
use ModernGame\Database\Repository\AbstractRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\ServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractAdminController extends AbstractController
{
    protected const REPOSITORY_CLASS = '';
    protected const FIND_BY = 'id';

    public function postEntity(Request $request, ServiceInterface $service): JsonResponse
    {
        /** @var object $entity */
        $entity = $service->mapEntity($request);

        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository(static::REPOSITORY_CLASS);
        $repository->insert($entity);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function getEntity(Request $request): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(static::REPOSITORY_CLASS);
        $toSearch = $request->query->getInt(static::FIND_BY);

        return new JsonResponse(empty($toSearch) ? $repository->findAll() : [$repository->findBy([static::FIND_BY => $toSearch])]);
    }

    public function putEntity(Request $request, ServiceInterface $service): JsonResponse
    {
        /** @var object $entity */
        $entity = $service->mapEntityById($request);

        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository(static::REPOSITORY_CLASS);
        $repository->update($entity);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws ContentException
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        /** @var AbstractRepository $repository */
        $repository = $this->getDoctrine()->getRepository(static::REPOSITORY_CLASS);
        try {
            $repository->delete($request->query->getInt('id'));
        } catch (Exception $e) {
            throw new ContentException(['error' => 'Nic nie znaleziono.']);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
