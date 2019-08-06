<?php

namespace ModernGame\Service\User;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\ResetPasswordRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ResetPasswordType;
use ModernGame\Service\MailSenderService;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ResetPasswordService
{
    private $userProvider;
    private $form;
    private $formErrorHandler;
    private $repository;
    private $mailSender;

    private const RESET_EMAIL_SCHEMA = 'RESET_EMAIL_SCHEMA';

    public function __construct(
        UserProviderInterface $userProvider,
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        ResetPasswordRepository $repository,
        MailSenderService $mailSender
    ) {
        $this->userProvider = $userProvider;
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $repository;
        $this->mailSender = $mailSender;
    }

    /**
     * @param Request $request
     * @return array
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ArrayException
     */
    public function reset(Request $request): array
    {
        $form = $this->form->create(ResetPasswordType::class);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($form->getData()['username']);

        $token = $this->generateTokenToResetPassword($user);

        $this->repository->addNewToken(
            $user->getId(),
            $token
        );

        $this->mailSender->sendEmail(self::RESET_EMAIL_SCHEMA, $token, $user->getEmail());

        return [
            'message' => ['Link do resetu hasła został wysłąny na adres email podany przy rejestracji' => 'Dziękujemy Zespół Gemdust'],
            'timeout' => 20000
        ];
    }

    private function generateTokenToResetPassword(User $user): string
    {
        return md5(uniqid() . $user->getUsername() . $user->getPassword() . date('Y-m-d H:i:s'));
    }
}
