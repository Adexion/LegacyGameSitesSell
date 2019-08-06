<?php

namespace ModernGame\Service\Connection;

use DateTime;
use ModernGame\Exception\ForbiddenOperationException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MojangPlayerService
{
    const MOJANG_GET_UUID_URL = 'https://api.mojang.com/users/profiles/minecraft/';
    const STEVE_USER_UUID = '8667ba71b85a4004af54457a9734eed7';

    private $userProvider;
    private $encoderFactory;
    private $client;

    public function __construct(
        UserProviderInterface $userProvider,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->client = new ClientFactory();
        $this->userProvider = $userProvider;
        $this->encoderFactory = $encoderFactory;
    }

    public function getUUID(string $userName): string
    {
        $user = json_decode(file_get_contents(self::MOJANG_GET_UUID_URL . $userName), true);

        if (empty($user)) {
            return self::STEVE_USER_UUID;
        }

        return $user['id'];
    }

    /**
     * @param array $loginData
     * 
     * @return array
     * @throws ForbiddenOperationException
     */
    public function loginIn(array $loginData): array
    {
        $user = $this->getUserByUsername($loginData['username'] ?? null);

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

    private function isUserLoggedRegistered(UserInterface $user, array $loginData)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        if (!$encoder->isPasswordValid($user->getPassword(), $loginData['password'] ?? null, $user->getSalt())) {
            throw new ForbiddenOperationException();
        }
    }

    private function getUserByUsername(string $username): UserInterface
    {
        try {
            return $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $exception) {
            throw new ForbiddenOperationException();
        }
    }

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
