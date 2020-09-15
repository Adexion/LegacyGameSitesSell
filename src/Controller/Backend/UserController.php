<?php

namespace ModernGame\Controller\Backend;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Form\ChangeUserType;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\User\LoginUserService;
use ModernGame\Service\User\RegisterService;
use ModernGame\Service\User\ResetPasswordService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;

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
    public function register(Request $request, RegisterService $register): JsonResponse
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
    public function login(Request $request, LoginUserService $login): JsonResponse
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
    public function getUserData(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
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
    public function getItemList(CustomSerializer $serializer): JsonResponse
    {
        return new JsonResponse([
            'itemList' => $serializer->serialize(
                $this
                    ->getDoctrine()
                    ->getRepository(UserItem::class)->findBy(['user' => $this->getUser()->getId()])
            )->toArray()
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
    public function resetPassword(Request $request, ResetPasswordService $resetPassword): JsonResponse
    {
        return new JsonResponse(['status' => $resetPassword->sendResetEmail($request)]);
    }

    /**
     * Update user data
     *
     * Updating user data after login to an account for now.
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
    public function update(Request $request, RegisterService $register): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $request->request->add(['id' => $user->getId()]);

        $register->update($request, ChangeUserType::class);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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
     *          @SWG\Property(property="token",type="string"),
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
     *          @SWG\Property(property="password", type="string"),
     *          @SWG\Property(property="token", type="string"),
     *     )
     * )
     */
    public function resetFromToken(Request $request, ResetPasswordService $resetPassword): JsonResponse
    {
        $resetPassword->resetPassword($request);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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
    public function loginMinecraft(Request $request, MojangPlayerService $player): JsonResponse
    {
        return new JsonResponse($player->loginIn($request));
    }

    /**
     * Execute User Item
     *
     * Get user specific item and send it as command for player on server
     * !!!Caution!!! It doesn't check user status on the server!
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     type="object",
     *     in="body",
     *     name="JSON",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="itemId",
     *              type="integer"
     *          )
     *     )
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function itemExecute(Request $request, RCONService $rcon, UserProviderInterface $userProvider): JsonResponse
    {
        return new JsonResponse(null, $rcon->executeItem(
            $request->request->getInt('itemId'),
            $userProvider->loadUserByUsername($request->request->getInt('username'))
        ));
    }

    /**
     * Execute User Item list
     *
     * Get user item list and send it as command for player on server
     * !!!Caution!!! It doesn't check user status on the server!
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=204,
     *     description="Evertythig works",
     * )
     */
    public function itemListExecute(Request $request, RCONService $rcon, UserProviderInterface $userProvider): JsonResponse
    {
        return new JsonResponse(null, $rcon->executeItem(
            null,
            $userProvider->loadUserByUsername($request->request->getInt('username'))
        ));
    }
}
