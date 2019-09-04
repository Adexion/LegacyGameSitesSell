<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\User\LoginUserService;
use ModernGame\Service\User\RegisterService;
use ModernGame\Service\User\ResetPasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function register(Request $request, RegisterService $register)
    {
        $register->register($request);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    public function login(Request $request, LoginUserService $login)
    {
        return new JsonResponse(['token' =>  $login->getToken($request)]);
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

    public function loginInLauncher(Request $request, MojangPlayerService $player)
    {
        return new JsonResponse($player->loginIn($request));
    }
}
