<?php

namespace ModernGame\Service\Connection\Minecraft;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Entity\User;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;
use ModernGame\Service\User\LoginUserService;
use Symfony\Component\HttpFoundation\Request;

class MojangPlayerService
{
    const MOJANG_GET_UUID_URL = 'https://api.mojang.com/users/profiles/minecraft/';
    const MOJANG_AUTH_URL = 'https://authserver.mojang.com/authenticate';
    const STEVE_USER_UUID = '8667ba71b85a4004af54457a9734eed7';

    private RestApiClient $client;
    private LoginUserService $loginUserService;

    public function __construct(
        LoginUserService $loginUserService,
        RestApiClient $client
    ) {
        $this->loginUserService = $loginUserService;
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function loginIn(Request $request): array
    {
        $user = $this->loginUserService->getUser($request);

        if ($this->getUUID($user->getUsername()) !== MojangPlayerService::STEVE_USER_UUID) {
            $user->setPassword($request->request->get('password'));
            $mojangPlayer = $this->loginByMojangAPI($user);

            if (isset($mojangPlayer['error'])) {
                throw new ContentException($mojangPlayer);
            }

            return $mojangPlayer;
        }

        $profile = $this->buildNonPremiumProfile($user->getUsername());

        return [
            'email' => $user->getEmail(),
            'accessToken' => md5(uniqid(rand(), true)),
            'clientToken' => md5(uniqid(rand(), true)),
            'selectedProfile' => $profile,
            'availableProfiles' => [$profile],
            'banned' => false
        ];
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function getUUID(?string $userName): string
    {
        if (empty($userName)) {
            throw new ContentException(['username' => 'Pole nie może być puste.']);
        }

        $mojangPlayer = json_decode($this->client->request(
            RestApiClient::GET,
            self::MOJANG_GET_UUID_URL . $userName
        ), true);

        return empty($mojangPlayer) ? self::STEVE_USER_UUID : $mojangPlayer['id'];
    }

    public function loginByMojangAPI(User $user): ?array
    {
        $mojangPlayer = json_decode($this->client->request(
            RestApiClient::POST,
            self::MOJANG_AUTH_URL, [
                'body' => json_encode(
                    [
                        'agent' => [
                            'name' => "Minecraft",
                            'version' => 1
                        ],
                        'username' => $user->getEmail(),
                        'password' => $user->getPassword()
                    ])
            ]
        ), true);

        return $mojangPlayer;
    }

    private function buildNonPremiumProfile(string $username): array
    {
        return [
            'agent' => 'minecraft',
            'id' => self::STEVE_USER_UUID,
            'userId' => self::STEVE_USER_UUID,
            'name' => $username,
            'createdAt' => (new DateTime())->getTimestamp(),
            'legacyProfile' => false,
            'suspended' => false,
            'tokenId' => (string)rand(1000000, 9999999),
            'paid' => true,
            'migrated' => false
        ];
    }
}
