<?php

namespace MNGame\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\ResetPassword;
use MNGame\Database\Entity\User;
use MNGame\Database\Repository\UserRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\RegisterType;
use MNGame\Form\ResetPasswordType;
use MNGame\Form\ResetType;
use MNGame\Service\Mail\MailSenderService;
use MNGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RegisterService
{
    private FormFactoryInterface $form;
    private FormErrorHandler $formErrorHandler;
    private UserRepository $userRepository;
    private WalletService $walletService;
    private UserPasswordHasherInterface $passwordEncoder;
    private MailSenderService $mailSenderService;
    private EntityManagerInterface $entityManager;
    private UserProviderInterface $userProvider;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        UserRepository $userRepository,
        WalletService $wallet,
        UserProviderInterface $userProvider,
        MailSenderService $mailSenderService,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->userRepository = $userRepository;
        $this->walletService = $wallet;
        $this->userProvider = $userProvider;
        $this->mailSenderService = $mailSenderService;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws ContentException
     */
    public function register(Request $request): ?int
    {
        $user = new User();
        $form = $this->form->create(RegisterType::class, $user);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));

        $this->userRepository->registerUser($user);
        $this->walletService->create($user);

        return $user->getId();
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
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
        }

        $this->userRepository->update($user);
    }

    /**
     * @throws ContentException
     */
    public function reset(Request $request)
    {
        $form = $this->form->create(ResetPasswordType::class);
        $form->handleRequest($request);

        $this->formErrorHandler->handle($form);

        $user = $this->userProvider->loadUserByIdentifier($form->getData()['username']);
        $token = md5(serialize($user).date('Y-m-d H:i:s'));

        $resetPassword = new ResetPassword();

        $resetPassword->setUser($user);
        $resetPassword->setToken($token);

        $this->mailSenderService->sendEmailBySchema('1', $token, $user->getEmail());
        $this->entityManager->persist($resetPassword);
        $this->entityManager->flush();
    }


    /**
     * @throws ContentException
     */
    public function setPassword(Request $request)
    {
        /** @var ResetPassword $resetToken */
        $resetToken = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $request->request->get('token')]);
        if (!$resetToken) {
            throw new ContentException(['token' => 'This value is not valid.']);
        }

        $form = $this->form->create(ResetType::class, $resetToken->getUser());
        $form->handleRequest($request);

        $this->formErrorHandler->handle($form);

        /** @var User $user */
        $user = $form->getData();
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->remove($resetToken);
        $this->entityManager->flush();
    }
}
