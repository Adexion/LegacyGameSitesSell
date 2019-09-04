<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController
{
    const PLAYER_AVATAR = 'https://crafatar.com/avatars/';

    public function getPlayerAvatar(Request $request, MojangPlayerService $player)
    {
        $link = self::PLAYER_AVATAR . $player->getUUID($request->query->get('username'));

        return new JsonResponse(['link' => $link]);
    }

    //ToDo: Add tests when rcon service be written
    public function getPlayerList(RCONService $rcon)
    {
        return new JsonResponse($rcon->getPlayerList());
    }
}
