<?php

namespace ModernGame\Service\User;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Form\RegisterType;
use ModernGame\Form\ResetType;
use ModernGame\Form\UserType;
use ModernGame\Exception\ArrayException;
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

    public function register(Request $request)
    {
        $user = new User();
        $form = $this->form->create(RegisterType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $this->checkIPAddress($request->getClientIp());

        $user->setIpAddress($request->getClientIp());
        $user->setPassword($this->setEncodedPassword($user));

        $this->userRepository->registerUser($user);
        $this->walletService->create($user->getId());

        return $user->getId();
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = $this->userRepository->find($request->request->get('id'));

        $form = $this->form->create(UserType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        if (strpos($user->getPassword(), '$2a$') === false) {
            $user->setPassword($this->setEncodedPassword($user));
        }

        $this->userRepository->update($user);
    }

    public function updatePassword(Request $request) {
        /** @var User $user */
        $user = $this->userRepository->find($request->request->get('id'));

        $form = $this->form->create(ResetType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        if (strpos($user->getPassword(), '$2a$') === false) {
            $user->setPassword($this->setEncodedPassword($user));
        }

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

    private function checkIPAddress(?string $clientIp)
    {
        $user = $this->userRepository->findOneBy(['ipAddress' => $clientIp]);

        if (!empty($user)) {
            throw new ArrayException(['ipAddress' => "Podany adres IP jest już zarejestrowany w systemie."]);
        }
    }
}
