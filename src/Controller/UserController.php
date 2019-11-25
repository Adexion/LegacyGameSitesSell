<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\User;
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
    /**
     * Registration of user in system without possibility to use admin endpoints
     *
     * This call a request to register a new user witch can use all available for him endpoints, with 0 coins on account
     * but with stored all needed data to give him possibility to buy an items, play'n on server or easy way to have secure
     * an account password.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(
     *              property="password",
     *              type="object",
     *              @SWG\Property(property="first", type="string"),
     *              @SWG\Property(property="second", type="string"),
     *          ),
     *          @SWG\Property(property="rules", type="string"),
     *          @SWG\Property(property="reCaptcha", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad credentials",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="password", type="string"),
     *          @SWG\Property(property="rules", type="string"),
     *          @SWG\Property(property="reCaptcha", type="string")
     *     )
     * )
     */
    public function register(Request $request, RegisterService $register)
    {
        $register->register($request);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * Login into application
     *
     * Way to login in to website and get permission to all of functionality what user have.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
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

    /**
     * Get user
     *
     * Way to get user information like eg.: roles, login and e-mail address
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Returns user",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="login", type="string"),
     *          @SWG\Property(property="email", type="string"),
     *          @SWG\Property(property="roles", type="object")
     *     )
     * )
     */
    public function getUserData(Request $request) {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }

    /**
     * Sending e-mail for reset password
     *
     * If it is possible, an email will be sent to user email given on registration.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="obect",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="status", type="integer")
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad credentials",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string")
     *     )
     * )
     */
    public function resetPassword(Request $request, ResetPasswordService $resetPassword)
    {
        return new JsonResponse(['status' => $resetPassword->sendResetEmail($request)]);
    }

    /**
     * Update user data
     *
     * Updating user password after login to an account.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="password",
     *              type="object",
     *              @SWG\Property(property="first", type="string"),
     *              @SWG\Property(property="second", type="string"),
     *          ),
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad credentials",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="password", type="string")
     *     )
     * )
     */
    public function update(Request $request, RegisterService $register)
    {
        $register->updatePassword($request, $this->getUser());

        return new JsonResponse();
    }

    /**
     * Set new password after reset
     *
     * Updating user password after give a token.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="password",
     *              type="object",
     *              @SWG\Property(property="first", type="string"),
     *              @SWG\Property(property="second", type="string"),
     *          ),
     *     )
     * ),
     * @SWG\Parameter(
     *     name="token",
     *     in="path",
     *     type="string"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad credentials",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="password", type="string"),
     *          @SWG\Property(property="token", type="string"),
     *     )
     * )
     */
    public function resetFromToken(Request $request, ResetPasswordService $resetPassword, string $token)
    {
        $resetPassword->resetPassword($request, $token);

        return new JsonResponse();
    }

    /**
     * Get an user bought items
     *
     * After bought item list by user, items form list goes to user. This endpoint sgive you possibility to get that items.
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works"
     * )
     */
    public function getItemList()
    {
        return new JsonResponse([
            'itemList' => $this->getDoctrine()
                ->getRepository(UserItem::class)->findBy(['user' => $this->getUser()->getId()])
        ]);
    }

    /**
     * Login into launcher
     *
     * Way to login in to for check is user registered for servers or launcher.
     * Gives you more information eg.: is premium user or user uuid
     * Can not be use. Normally login can be use instead.
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *          @SWG\Property(property="password", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="return structure like mojang endpoint for premium but with fake data for non-premium user",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="agent", type="string"),
     *          @SWG\Property(property="id", type="string"),
     *          @SWG\Property(property="userId", type="string"),
     *          @SWG\Property(property="name", type="string"),
     *          @SWG\Property(property="createdAt", type="string"),
     *          @SWG\Property(property="legacyProfile", type="boolean"),
     *          @SWG\Property(property="suspended", type="boolean"),
     *          @SWG\Property(property="tokenId", type="integer"),
     *          @SWG\Property(property="paid", type="boolean"),
     *          @SWG\Property(property="migrated", type="boolean")
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
    public function loginMinecraft(Request $request, MojangPlayerService $player)
    {
        return new JsonResponse($player->loginIn($request));
    }
}
