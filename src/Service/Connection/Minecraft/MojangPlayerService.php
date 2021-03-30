<?php

namespace MNGame\Service\Connection\Minecraft;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Exception\ContentException;
use MNGame\Service\Connection\ApiClient\RestApiClient;
use MNGame\Service\User\LoginUserService;
use Symfony\Component\HttpFoundation\Request;

class MojangPlayerService
{
    public const MOJANG_GET_UUID_URL = 'https://api.mojang.com/users/profiles/minecraft/';
    public const MOJANG_AUTH_URL = 'https://authserver.mojang.com/';
    public const STEVE_USER_UUID = '8667ba71b85a4004af54457a9734eed7';

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
     * @throws ContentException
     */
    public function loginIn(Request $request): array
    {
        $mojangPlayer = $this->loginByMojangAPI($request);
        if (!isset($mojangPlayer['error'])) {
            return $mojangPlayer;
        }

        $user = $this->loginUserService->getUser($request);
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
    public function additionalAccountAction(Request $request, $type): ?array
    {
        $response = json_decode($this->client->request(
            RestApiClient::POST,
            self::MOJANG_AUTH_URL . $type, [
                'body' => json_encode([
                    'accessToken' => $request->request->get('accessToken'),
                    'clientToken' => $request->request->get('clientToken'),
                ])
            ]
        ), true);

        if (isset($response['error'])) {
            throw new ContentException($response);
        }

        return $response;
    }

    private function loginByMojangAPI(Request $request): ?array
    {
        return json_decode($this->client->request(
            RestApiClient::POST,
            self::MOJANG_AUTH_URL . 'authenticate', [
                'body' => json_encode([
                    'agent' => [
                        'name' => "Minecraft",
                        'version' => 1
                    ],
                    'username' => $request->request->get('username'),
                    'password' => $request->request->get('password')
                ])
            ]
        ), true);
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
