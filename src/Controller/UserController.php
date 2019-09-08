<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\UserItem;
use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\User\LoginUserService;
use ModernGame\Service\User\RegisterService;
use ModernGame\Service\User\ResetPasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class UserController extends Controller
{
    public function register(Request $request, RegisterService $register)
    {
        $register->register($request);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="content",
     *     in="body",
     *     type="array",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *          @SWG\Property(property="password", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns user token",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="token", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad credentials",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="error", type="string")
     *     )
     * )
     */
    public function login(Request $request, LoginUserService $login)
    {
        return new JsonResponse(['token' => $login->getToken($request)]);
    }

    public function resetPassword(Request $request, ResetPasswordService $resetPassword)
    {
        return new JsonResponse(['status' => $resetPassword->sendResetEmail($request)]);
    }

    public function update(Request $request, RegisterService $register)
    {
        $register->updatePassword($request, $this->getUser());

        return new JsonResponse();
    }

    public function resetFromToken(Request $request, ResetPasswordService $resetPassword, string $token)
    {
        $resetPassword->resetPassword($request, $token);

        return new JsonResponse();
    }

    public function getItemList()
    {
        return new JsonResponse([
            'itemList' => $this->getDoctrine()
                ->getRepository(UserItem::class)->findBy(['userId' => $this->getUser()->getId()])
        ]);
    }

    public function loginMinecraft(Request $request, MojangPlayerService $player)
    {
        return new JsonResponse($player->loginIn($request));
    }
}
