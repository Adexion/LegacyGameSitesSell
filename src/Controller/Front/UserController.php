<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route(name="user-profile", path="/user/profile")
     */
    public function userProfile()
    {
        return $this->render('front/page/user.profile.html.twig');
    }

    /**
     * @Route(name="edit-profile", path="/user/edit")
     */
    public function updateProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user->toArray());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userData = $form->getData();

            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);

            if ($userData['password'] ?? '') {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            }

            $userRepository->update($user);
            return $this->redirectToRoute('user-profile');
        }

        return $this->render('front/page/edit.profile.html.twig', [
            'edit_form' => $form->createView()
        ]);
    }
}
