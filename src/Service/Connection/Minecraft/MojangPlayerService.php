<?php

namespace ModernGame\Service\Connection\Minecraft;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ArrayException;
use ModernGame\Exception\ForbiddenOperationException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MojangPlayerService
{
    const MOJANG_GET_UUID_URL = 'https://api.mojang.com/users/profiles/minecraft/';
    const STEVE_USER_UUID = '8667ba71b85a4004af54457a9734eed7';

    private $userProvider;
    private $encoderFactory;
    /**
     * @var RestApiClient
     */
    private $client;

    public function __construct(
        UserProviderInterface $userProvider,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
        $this->client = new RestApiClient();
    }

    /**
     * @throws GuzzleException
     * @throws ArrayException
     */
    public function getUUID(?string $userName): string
    {
        if (empty($userName)) {
            throw new ArrayException(['username' => 'Pole nie może być puste.']);
        }

        $user = json_decode($this->client
                ->request(RestApiClient::GET, self::MOJANG_GET_UUID_URL . $userName),
            true);

        if (empty($user)) {
            return self::STEVE_USER_UUID;
        }

        return $user['id'];
    }

    /**
     * @throws ForbiddenOperationException
     * @throws GuzzleException
     * @throws ArrayException
     */
    public function loginIn(array $loginData): array
    {
        $user = $this->userProvider->loadUserByUsername($username['username'] ?? null);

        $this->isUserLoggedRegistered($user, $loginData);

        $profile = $this->buildProfile(
            $loginData['username']
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
     * @throws ForbiddenOperationException
     */
    private function isUserLoggedRegistered(UserInterface $user, array $loginData)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        if (!$encoder->isPasswordValid($user->getPassword(), $loginData['password'] ?? null, $user->getSalt())) {
            throw new ForbiddenOperationException();
        }
    }

    /**
     * @throws GuzzleException
     * @throws ArrayException
     */
    private function buildProfile(string $username): array
    {
        $uuid = $this->getUUID($username);

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
