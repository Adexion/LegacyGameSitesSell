<?php

namespace MNGame\Controller\API;

use MNGame\Database\Entity\User;
use MNGame\Database\Entity\UserItem;
use MNGame\Exception\ContentException;
use MNGame\Form\UserEditType;
use MNGame\Service\Minecraft\ExecuteItemService;
use MNGame\Validator\FormErrorHandler;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route(name="api-edit-profile", path="/api/user", methods={"GET"})
     */
    public function profile(): Response
    {
        return new JsonResponse($this->getUser());
    }

    /**
     * @Route(name="api-edit-profile", path="/api/user/edit", methods={"PUT"})
     *
     * @throws ContentException
     */
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordEncoder, FormErrorHandler $errorHandler): Response
    {
        $lastPassword = $this->getUser()->getPassword();
        $form = $this->createForm(UserEditType::class, $this->getUser());
        $form->handleRequest($request);

        $errorHandler->handle($form);

        /** @var User $user */
        $user = $form->getData();
        if ($user->getPassword() !== $lastPassword) {
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(name="api-equipment", path="/api/user/itemlist", methods={"GET"})
     */
    public function equipmentProfile(Request $request): Response
    {
        return new JsonResponse([
            'userItemList' => $this->getDoctrine()->getRepository(UserItem::class)->findBy(['user' => $this->getUser()]),
        ], $request->query->getInt('code'));
    }

    /**
     * @Route(name="api-item-profile", path="/api/user/item/execute", methods={"POST"})
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function itemExecute(Request $request, ExecuteItemService $executeItemService): Response
    {
        return new JsonResponse([], $executeItemService->executeItem($this->getUser(), $request->request->getInt('itemId')));
    }

    /**
     * @Route(name="item-list-profile", path="/user/itemlist/execute", methods={"POST"})
     *
     * @throws ContentException
     * @throws ReflectionException
     */
    public function itemListExecute(Request $request, ExecuteItemService $executeItemService): Response
    {
        return new JsonResponse([], $executeItemService->executeItem($this->getUser()));
    }
}
