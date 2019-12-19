<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\ResetPassword;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\ResetPasswordRepository;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ResetPasswordType;
use ModernGame\Form\ResetType;
use ModernGame\Service\Mail\MailSenderService;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ResetPasswordService
{
    private UserProviderInterface $userProvider;
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private ResetPasswordRepository $repository;
    private UserRepository $userRepository;
    private MailSenderService $mailSender;
    private UserPasswordEncoderInterface $passwordEncoder;

    private const RESET_EMAIL_SCHEMA = '1';

    public function __construct(
        UserProviderInterface $userProvider,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        ResetPasswordRepository $repository,
        UserRepository $userRepository,
        MailSenderService $mailSender,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userProvider = $userProvider;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->mailSender = $mailSender;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContentException
     */
    public function sendResetEmail(Request $request): int
    {
        $form = $this->form->create(ResetPasswordType::class);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($form->getData()['username']);

        $token = $this->generateTokenToResetPassword($user);

        $this->repository->addNewToken($user, $token);

        return $this->mailSender->sendEmail(self::RESET_EMAIL_SCHEMA, $token, $user->getEmail());
    }

    /**
     * @throws ContentException
     */
    public function resetPassword(Request $request)
    {
        /** @var ResetPassword $reset */
        $reset = $this->repository->findOneBy(['token' => $request->request->get('token')]);

        if (empty($reset)) {
            throw new ContentException(['token' => 'Ta wartość jest nieprawidłowa.']);
        }

        /** @var User $user */
        $user = $reset->getUser();

        $form = $this->form->create(ResetType::class, null, ['method'  => 'PUT']);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $this->passwordEncoder->encodePassword($user, $user->getPassword());
    }

    private function generateTokenToResetPassword(User $user): string
    {
        return md5(uniqid() . $user->getUsername() . $user->getPassword() . date('Y-m-d H:i:s'));
    }
}
