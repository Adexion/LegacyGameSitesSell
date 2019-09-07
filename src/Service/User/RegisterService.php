<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Form\RegisterType;
use ModernGame\Form\UpdatePasswordType;
use ModernGame\Form\UserType;
use ModernGame\Exception\ContentException;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterService
{
    private $form;
    private $formErrorHandler;
    private $userRepository;
    private $walletService;
    private $passwordEncoder;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        UserRepository $userRepository,
        WalletService $wallet,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->userRepository = $userRepository;
        $this->walletService = $wallet;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws ContentException
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->form->create(RegisterType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $user->setIpAddress($request->getClientIp());
        $user->setPassword($this->setEncodedPassword($user));

        $this->userRepository->registerUser($user);
        $this->walletService->create($user->getId());

        return $user->getId();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContentException
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = $this->userRepository->find($request->request->get('id'));

        $form = $this->form->create(UserType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $user->setPassword($this->setEncodedPassword($user));

        $this->userRepository->update($user);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContentException
     */
    public function updatePassword(Request $request, User $user)
    {
        $form = $this->form->create(UpdatePasswordType::class, null, ['method' => 'PUT']);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $user->setPassword($request->request->get('password')['first']);
        $user->setPassword($this->setEncodedPassword($user));

        $this->userRepository->update($user);
    }

    private function setEncodedPassword(User $user)
    {
        return preg_replace(
            '/^[$2y$]+/',
            '\$2a$',
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }
}
