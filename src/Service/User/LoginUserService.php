<?php

namespace MNGame\Service\User;

use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\User;
use MNGame\Form\MojangLoginType;
use MNGame\Database\Entity\Token;
use MNGame\Exception\ContentException;
use MNGame\Validator\FormErrorHandler;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use MNGame\Database\Repository\UserRepository;
use MNGame\Database\Repository\TokenRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginUserService
{
    private UserProviderInterface $userProvider;
    private UserPasswordHasherInterface $passwordEncoder;
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private TokenRepository $repository;
    private UserRepository $userRepository;

    public function __construct(
        UserProviderInterface $userProvider,
        UserPasswordHasherInterface $passwordEncoder,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenRepository $repository,
        UserRepository $userRepository
    ) {
        $this->userProvider     = $userProvider;
        $this->passwordEncoder  = $passwordEncoder;
        $this->form             = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository       = $repository;
        $this->userRepository   = $userRepository;
    }

    /**
     * @throws ContentException
     */
    public function getUser(Request $request): User
    {
        $form = $this->form->create(MojangLoginType::class);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        /** @var User $user */
        try {
            $user = $this->userProvider->loadUserByIdentifier($request->request->get('username'));
        } catch (UserNotFoundException) {
        }

        if (empty($user) || !$this->passwordEncoder->isPasswordValid($user, $request->request->get('password'))) {
            throw new BadCredentialsException();
        }

        return $user;
    }

    /**
     * @throws ContentException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getToken(Request $request): string
    {
        return $this->generateToken($this->getUser($request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function generateToken(User $user): string
    {
        $token = new Token();

        $token->setToken(hash('sha256', uniqid('', md5(date('Y-m-d H:i:s'), $user->getUsername()))));
        $token->setUser($user);

        $this->repository->insert($token);

        return $token->getToken();
    }
}
