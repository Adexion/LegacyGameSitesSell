<?php

namespace ModernGame\Controller\Backend;

use ModernGame\Service\Connection\Minecraft\RCONService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    const PLAYER_AVATAR = 'http://cravatar.eu/avatar/';

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
    public function getMinecraftAvatar(Request $request): JsonResponse
    {
        $link = self::PLAYER_AVATAR . $request->query->get('username');

        return new JsonResponse(['avatar' => $link]);
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
    public function getPlayerList(RCONService $rcon): JsonResponse
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
    public function getPlayer(Request $request, RCONService $rcon): JsonResponse
    {
        return new JsonResponse([
            'online' => strstr($rcon->getPlayerList(), $request->query->get('username')) !== false
        ]);
    }
}
