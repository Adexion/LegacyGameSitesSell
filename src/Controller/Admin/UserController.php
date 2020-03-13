<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\User;
use ModernGame\Form\UserType;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\User\RegisterService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class UserController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = User::class;

    /**
     * Get list of registered users
     *
     * @SWG\Tag(name="Admin/User")
     * @SWG\Parameter(
     *     type="string",
     *     name="id",
     *     in="query"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  property="id"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="email"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="username"
     *              ),
     *              @SWG\Property(
     *                  type="array",
     *                  property="roles",
     *                  @SWG\Items(type="string")
     *              )
     *          )
     *     )
     * )
     */
    public function getEntity(Request $request, CustomSerializer $serializer): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(static::REPOSITORY_CLASS);
        $toSearch = $request->query->getInt(static::FIND_BY);

        return new JsonResponse($serializer->serialize(
            empty($id) ? $repository->search() : [$repository->search([static::FIND_BY => $toSearch])]
        )->toArray());
    }

    /**
     * Change user by panel admin
     *
     * @SWG\Tag(name="Admin/User")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function putUser(Request $request, RegisterService $register)
    {
        $register->update($request, UserType::class,);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * Delete user from database and all his informations
     *
     * @SWG\Tag(name="Admin/User")
     * @SWG\Parameter(type="integer", in="query", name="id")
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
