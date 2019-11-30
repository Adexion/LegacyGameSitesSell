<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Service\User\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class UserController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = User::class;

    /**
     * @SWG\Tag(name="Admin/User")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getEntity(Request $request): JsonResponse
    {
        return parent::getEntity($request);
    }

    /**
     * @SWG\Tag(name="Admin/User")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putUser(Request $request, RegisterService $register)
    {
        $register->update($request);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/User")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
