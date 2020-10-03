<?php

namespace ModernGame\Controller\Front;

use LogicException;
use ModernGame\Database\Entity\ResetPassword;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Form\LoginType;
use ModernGame\Form\RegisterType;
use ModernGame\Form\ResetPasswordType;
use ModernGame\Form\ResetType;
use ModernGame\Service\Mail\MailSenderService;
use ModernGame\Service\User\WalletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

        $form  = $this->createForm(LoginType::class);
        if ($error instanceof BadCredentialsException) {
            $form
                ->get('_password')
                ->addError(new FormError('Login lub hasło jest nieprawidłowy.'));
        }

        return $this->render('front/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('index'),
            'login_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        UserRepository $userRepository,
        WalletService $walletService,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $userRepository->registerUser($user);
            $walletService->create($user);

            return $this->redirectToRoute('login');
        }

        return $this->render('front/page/register.html.twig', [
            'register_form' => $form->createView(),
            'site_key' => $this->getParameter('google')['siteKey']
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
                $user = $userProvider->loadUserByUsername($form->getData()['username']);
                $token = md5(serialize($user) . date('Y-m-d H:i:s'));

                $resetPassword = new ResetPassword();

                $resetPassword->setUser($user);
                $resetPassword->setToken($token);

                $send = $service->sendEmail('1', $token, $user->getEmail());
                $this->getDoctrine()->getManager()->persist($resetPassword);
                $this->getDoctrine()->getManager()->flush();
            } catch (UsernameNotFoundException $e) {
                $send = 1;
            }
        }

        return $this->render('front/page/forgotPassword.html.twig', [
            'send' => $send ?? false,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset/{token}", name="reset-password")
     */
    public function resetToken(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user-profile');
        }

        /** @var ResetPassword $resetToken */
        $resetToken = $this->getDoctrine()->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $resetToken) {
            $user = $resetToken->getUser();

            $user->setPassword($passwordEncoder->encodePassword($user, $form->getData()['password']));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->remove($resetToken);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('front/page/resetPassword.html.twig', [
            'form' => $form->createView(),
            'link' => '/reset/' . $token
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
