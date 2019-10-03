<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Content\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    public function setMessageContact(Request $request, TicketService $contact)
    {
        $contact = $contact->mapEntity($request);
        $this->getDoctrine()->getRepository(Ticket::class)->insert($contact);

        return new JsonResponse(['ticket' => $contact->getToken()]);
    }

    function getMessagesTicket(string $ticket)
    {
        $messages = $this->getDoctrine()->getRepository(Ticket::class)
            ->findBy(['token' => $ticket]);

        if (empty($messages)) {
            throw new ContentException(['ticket' => 'Ta wartość jest nieprawidłowa.']);
        }

        return new JsonResponse(['messages' => $messages]);
    }
}
