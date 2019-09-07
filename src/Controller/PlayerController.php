<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController
{
    const PLAYER_AVATAR = 'https://crafatar.com/avatars/';

    public function getMinecraftAvatar(Request $request, MojangPlayerService $player)
    {
        $link = self::PLAYER_AVATAR . $player->getUUID($request->query->get('username'));

        return new JsonResponse(['link' => $link]);
    }

    public function getPlayerList(RCONService $rcon)
    {
        return new JsonResponse(['list' => $rcon->getPlayerList()]);
    }
}
