<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\RegisterType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterService
{
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private UserRepository $userRepository;
    private WalletService $walletService;
    private UserPasswordEncoderInterface $passwordEncoder;

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
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContentException
     */
    public function update(Request $request, $typeClass)
    {
        /** @var User $user */
        $user = $this->userRepository->find($request->request->get('id'));
        $form = $this->form->create($typeClass, $user, ['method' => 'PUT']);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        if ($request->request->get('password')) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        }

        $this->userRepository->update($user);
    }

}
