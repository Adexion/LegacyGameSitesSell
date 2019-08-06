<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Contact;
use ModernGame\Database\Repository\ContactRepository;
use ModernGame\Service\Content\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends AbstractController
{
    public function deleteTicket(Request $request)
    {
        /** @var ContactRepository $contactRepository */
        $contactRepository = $this->getDoctrine()->getRepository(Contact::class);
        $contactRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putTicket(Request $request, ContactService $contact)
    {
        $contactEntity = $contact->getMappedTicket($request);
        $this->getDoctrine()->getRepository(Contact::class)->insert($contactEntity);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
