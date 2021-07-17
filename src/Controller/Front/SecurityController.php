<?php

namespace MNGame\Controller\Front;

use MNGame\Database\Entity\ResetPassword;
use MNGame\Database\Entity\User;
use MNGame\Database\Repository\UserRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\LoginType;
use MNGame\Form\RegisterType;
use MNGame\Form\ResetPasswordType;
use MNGame\Form\ResetType;
use MNGame\Service\Mail\MailSenderService;
use MNGame\Service\User\WalletService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);
        if ($error instanceof BadCredentialsException) {
            $form
                ->get('_password')
                ->addError(new FormError('Login lub hasło jest nieprawidłowy.'));
        }

        return $this->render('base/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('index'),
            'login_form' => $form->createView(),
            'register_form' => $this->createForm(RegisterType::class)->createView(),
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @throws ContentException
     */
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserProviderInterface $userProvider,
        WalletService $walletService,
        UserPasswordHasherInterface $passwordEncoder,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            $userRepository->registerUser($user);
            $walletService->create($user);

            if ($user->getReferral()) {
                try {
                    $referral = $userProvider->loadUserByIdentifier($user->getReferral());
                    $walletService->changeCash(1, $referral);
                } catch (UserNotFoundException) {
                }
            }

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $tokenStorage->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch($event, SecurityEvents::INTERACTIVE_LOGIN);

            return $this->redirectToRoute('index');
        }

        return $this->render('base/page/login.html.twig', [
            'login_form' => $this->createForm(LoginType::class)->createView(),
            'register_form' => $form->createView(),
            'site_key' => $this->getParameter('googleSiteKey'),
        ]);
    }

    /**
     * @Route("/reset", name="forgot-password")
     */
    public function forgotPassword(
        Request $request,
        UserProviderInterface $userProvider,
        MailSenderService $service
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            try {
                $user = $userProvider->loadUserByIdentifier($form->getData()['username']);
                $token = md5(serialize($user).date('Y-m-d H:i:s'));

                $resetPassword = new ResetPassword();

                $resetPassword->setUser($user);
                $resetPassword->setToken($token);

                $send = $service->sendEmailBySchema('1', $token, $user->getEmail());
                $this->getDoctrine()->getManager()->persist($resetPassword);
                $this->getDoctrine()->getManager()->flush();
            } catch (UserNotFoundException $e) {
                $send = 1;
            }
        }

        return $this->render('base/page/forgotPassword.html.twig', [
            'send' => $send ?? false,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/{token}", name="reset-password")
     */
    public function resetToken(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        string $token,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        /** @var ResetPassword $resetToken */
        $resetToken = $this->getDoctrine()->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);
        if (!$resetToken) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ResetType::class, $resetToken->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            $om = $this->getDoctrine()->getManager();
            $om->persist($user);
            $om->remove($resetToken);
            $om->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $tokenStorage->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch($event, SecurityEvents::INTERACTIVE_LOGIN);

            return $this->redirectToRoute('index');
        }

        return $this->render('base/page/resetPassword.html.twig', [
            'form' => $form->createView(),
            'link' => '/reset/'.$token,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
