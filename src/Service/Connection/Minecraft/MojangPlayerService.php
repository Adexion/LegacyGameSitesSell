<?php

namespace ModernGame\Service\Connection\Minecraft;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;
use ModernGame\Service\User\LoginUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MojangPlayerService
{
    const MOJANG_GET_UUID_URL = 'https://api.mojang.com/users/profiles/minecraft/';
    const STEVE_USER_UUID = '8667ba71b85a4004af54457a9734eed7';

    private $userProvider;
    private $encoderFactory;
    private $client;
    private $loginUserService;

    public function __construct(
        UserProviderInterface $userProvider,
        EncoderFactoryInterface $encoderFactory,
        LoginUserService $loginUserService,
        RestApiClient $client
    ) {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
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

        $profile = $this->buildProfile(
            $user->getUsername(),
            $this->getUUID($user->getUsername())
        );

        return [
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

    private function buildProfile(string $username, string $uuid): array
    {
        return [
            'agent' => 'minecraft',
            'id' => $uuid,
            'userId' => $uuid,
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
