<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Regulation;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\UserRepository;
use ModernGame\Service\User\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function deleteUser(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $userRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putUser(Request $request, RegisterService $register)
    {
        $register->update($request);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getUserById(Request $request)
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Regulation::class)->find($request->query->getInt('id'))
        );
    }

    public function getUsers()
    {
        return new JsonResponse(
            $this->getDoctrine()->getRepository(Regulation::class)->findAll()
        );
    }
}
