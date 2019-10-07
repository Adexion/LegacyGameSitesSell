<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Service\Content\TicketService;
use ModernGame\Serializer\CustomSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    public function deleteTicket(Request $request)
    {
        /** @var TicketRepository $contactRepository */
        $contactRepository = $this->getDoctrine()->getRepository(Ticket::class);
        $contactRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function putTicket(Request $request, TicketService $contact)
    {
        $contactEntity = $contact->mapEntity($request);

        $this->getDoctrine()->getRepository(Ticket::class)->insert($contactEntity);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    public function getTicket(Request $request, CustomSerializer $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(
            $serializer->toArray(empty($id) ? $repository->findAll() : $repository->find($id))
        );
    }
}
