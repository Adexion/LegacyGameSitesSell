<?php

namespace MNGame\Service\User;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\Token;
use MNGame\Database\Entity\User;
use MNGame\Database\Repository\TokenRepository;
use MNGame\Database\Repository\UserRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\MojangLoginType;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginUserService
{
    private UserProviderInterface $userProvider;
    private UserPasswordEncoderInterface $passwordEncoder;
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private TokenRepository $repository;
    private UserRepository $userRepository;

    public function __construct(
        UserProviderInterface $userProvider,
        UserPasswordEncoderInterface $passwordEncoder,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TokenRepository $repository,
        UserRepository $userRepository
    ) {
        $this->userProvider = $userProvider;
        $this->passwordEncoder = $passwordEncoder;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $repository;
        $this->userRepository = $userRepository;
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
            $user = $this->userProvider->loadUserByUsername($request->request->get('username'));
        } catch (UsernameNotFoundException $e) {
            $user = $this->userRepository->findOneBy(['email' => $request->request->get('username')]);
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
