<?php

namespace MNGame\Controller\API;

use Exception;

use MNGame\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route(name="api-contact", path="/api/contact", methods={"GET"})
     *
     * @throws Exception
     */
    public function getContact(Request $request, ContactService $contactService): Response
    {
        return new JsonResponse([$contactService->getContactForm($request)]);
    }
}