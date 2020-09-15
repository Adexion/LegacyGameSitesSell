<?php

namespace ModernGame\Controller\Front;

use ModernGame\Database\Entity\User;
use ModernGame\Database\Entity\UserItem;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Form\UserEditType;
use ModernGame\Service\Connection\Minecraft\RCONService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

;

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
    public function updateProfile(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
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

    /**
     * @Route(name="equipment-profile", path="/user/equipment")
     */
    public function equipmentProfile(Request $request) {
        return $this->render('front/page/equipment.profile.html.twig', [
            'userItemList' => $this->getDoctrine()->getRepository(UserItem::class)->findBy(['user' => $this->getUser()]),
            'code' => $request->query->getInt('code')
        ]);
    }

    /**
     * @Route(name="item-profile", path="/user/item")
     */
    public function itemExecute(Request $request, RCONService $rcon, UserProviderInterface $userProvider)
    {
        if (strstr($rcon->getPlayerList(), $this->getUser()->getUsername()) === false) {
            return $this->redirectToRoute('equipment-profile', [
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        $rcon->executeItem($request->request->getInt('itemId'), $this->getUser(), true);

        return $this->redirectToRoute('equipment-profile', [
            'code' => Response::HTTP_OK
        ]);
    }

    /**
     * @Route(name="item-list-profile", path="/user/item/all")
     */
    public function itemListExecute(Request $request, RCONService $rcon, UserProviderInterface $userProvider)
    {
        if (strstr($rcon->getPlayerList(), $this->getUser()->getUsername()) === false) {
            return $this->redirectToRoute('equipment-profile', [
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        $rcon->executeItem(null, $this->getUser(), true);

        return $this->redirectToRoute('equipment-profile', [
            'code' => Response::HTTP_OK
        ]);
    }
}
