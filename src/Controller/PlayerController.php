<?php

namespace ModernGame\Controller;

use ModernGame\Service\Connection\Minecraft\MojangPlayerService;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class PlayerController extends AbstractController
{
    const PLAYER_AVATAR = 'https://crafatar.com/avatars/';

    /**
     * Get user minecraft avatar
     *
     * By minecraft username you should be able to get avatar of your skin
     *
     * @SWG\Tag(name="Player")
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
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
     *          @SWG\Property(property="username", type="string"),
     *     )
     * )
     */
    public function getMinecraftAvatar(Request $request, MojangPlayerService $player)
    {
        $link = self::PLAYER_AVATAR . $player->getUUID($request->query->get('username'));
        return new JsonResponse(['avatar' => base64_encode(file_get_contents($link))]);
    }

    /**
     * Get player list
     *
     * Get list of players actually being on the server
     *
     * @SWG\Tag(name="Player")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works"
     * )
     */
    public function getPlayerList(RCONService $rcon)
    {
        return new JsonResponse(['list' => $rcon->getPlayerList()]);
    }

    /**
     * Get status of player on server
     *
     * Get list of players actually being on the server
     *
     * @SWG\Tag(name="Player")
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="online", type="boolean"),
     *     )
     * )
     */
    public function getPlayer(Request $request, RCONService $rcon)
    {
        return new JsonResponse([
            'online' => strstr($rcon->getPlayerList(), $request->query->get('username')) !== false
        ]);
    }
}
