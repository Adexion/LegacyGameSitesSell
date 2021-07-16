<?php

namespace MNGame\Controller\API;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use MNGame\Exception\ContentException;
use MNGame\Service\User\LoginUserService;
use MNGame\Service\User\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/auth", name="api-auth", methods={"POST"})
     *
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ContentException
     */
    public function getToken(Request $request, LoginUserService $loginUserService): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([], RESPONSE::HTTP_FORBIDDEN);
        }

        return new JsonResponse([$loginUserService->getToken($request)]);
    }

    /**
     * @Route("/api/user", name="api-user", methods={"POST"})
     * @throws ContentException
     */
    public function register(Request $request, RegisterService $registerService): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([], RESPONSE::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['id' => $registerService->register($request)], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/reset", name="forgot-password", methods={"POST"})
     *
     * @throws ContentException
     */
    public function forgotPassword(Request $request, RegisterService $registerService): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([], RESPONSE::HTTP_FORBIDDEN);
        }

        $registerService->reset($request);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/reset", name="reset-password", methods={"PUT"})
     *
     * @throws ContentException
     */
    public function resetToken(Request $request, RegisterService $registerService): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([], RESPONSE::HTTP_FORBIDDEN);
        }

        $registerService->setPassword($request);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
