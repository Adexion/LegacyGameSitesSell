<?php

namespace ModernGame\Controller;

use App\General\Form\ResetType;
use ModernGame\Database\Entity\ResetPassword;
use ModernGame\Database\Entity\User;
use ModernGame\Exception\ArrayException;
use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
use ModernGame\Service\User\LoginUserService;
use ModernGame\Service\User\RegisterService;
use ModernGame\Service\User\ResetPasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends Controller
{
    const PLAYER_AVATAR = 'https://crafatar.com/avatars/';

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
        $register->updatePassword($request);

        return new JsonResponse();
    }

    public function getPlayerList(RCONService $rcon)
    {
        return new JsonResponse($rcon->getPlayerList());
    }

    public function resetFromToken(Request $request, ResetPasswordService $resetPassword, string $token)
    {
        $resetPassword->resetPassword($request, $token);

        return new JsonResponse();
    }

    public function getPlayerAvatar(Request $request, MojangPlayerService $player)
    {
        $link = self::PLAYER_AVATAR . $player->getUUID($request->query->get('username'));

        return new JsonResponse(['link' => $link]);
    }

    public function loginInLauncher(Request $request, MojangPlayerService $player)
    {
        return new JsonResponse($player->loginIn($request));
    }
}
