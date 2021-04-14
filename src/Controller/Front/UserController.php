<?php

namespace MNGame\Controller\Front;

use MNGame\Database\Entity\User;
use MNGame\Database\Entity\UserItem;
use MNGame\Database\Repository\UserRepository;
use MNGame\Exception\ContentException;
use MNGame\Form\UserEditType;
use MNGame\Service\Connection\Minecraft\ExecuteItemService;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route(name="user-profile", path="/user/profile")
     */
    public function userProfile()
    {
        return $this->render('base/page/user.profile.html.twig');
    }

    /**
     * @Route(name="edit-profile", path="/user/edit")
     */
    public function updateProfile(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ): Response {
        $lastPassword = $this->getUser()->getPassword();
        $form = $this->createForm(UserEditType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            if ($user->getPassword() !== $lastPassword) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            }

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user-profile');
        }

        return $this->render('base/page/edit.profile.html.twig', [
            'edit_form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="equipment-profile", path="/user/equipment")
     */
    public function equipmentProfile(Request $request): Response
    {
        return $this->render('base/page/equipment.profile.html.twig', [
            'userItemList' => $this->getDoctrine()->getRepository(UserItem::class)->findBy(['user' => $this->getUser()]),
            'code' => $request->query->getInt('code'),
        ]);
    }

    /**
     * @Route(name="item-profile", path="/user/item")
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function itemExecute(Request $request, ExecuteItemService $executeItemService): Response
    {
        return $this->redirectToRoute('equipment-profile', [
            'code' => $executeItemService->executeItem($this->getUser(), $request->request->getInt('itemId')),
        ]);
    }

    /**
     * @Route(name="item-list-profile", path="/user/item/all")
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function itemListExecute(Request $request, ExecuteItemService $executeItemService): Response
    {
        return $this->redirectToRoute('equipment-profile', [
            'code' => $executeItemService->executeItem($this->getUser()),
        ]);
    }
}
