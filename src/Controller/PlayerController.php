<?php

namespace ModernGame\Controller;

use ModernGame\Exception\ArrayException;
use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
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

    public function login(Request $request)
    {
        if (empty($this->getUser())) {
            throw new ArrayException(['Błąd walidacji' => 'Nieprawidłowe dane.']);
        }

        $user = $this->getUser();

        return new JsonResponse([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }

    public function resetPassword(Request $request, ResetPasswordService $resetPassword)
    {
        $resetPassword->reset($request);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
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

    public function getPlayerAvatar(Request $request, MojangPlayerService $player)
    {
        $link = self::PLAYER_AVATAR . $player->getUUID($request->query->get('username'));

        return new JsonResponse(['link' => $link]);
    }

    public function loginInLauncher(Request $request, MojangPlayerService $player)
    {
        return new JsonResponse($player->loginIn($request->request->all()));
    }
}
