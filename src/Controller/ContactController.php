<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Exception\ArrayException;
use ModernGame\Service\Content\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    public function setMessageContact(Request $request, TicketService $contact)
    {
        return new JsonResponse(['ticket' => $contact->setContactMessage($request)]);
    }

    function getMessagesTicket(string $ticket)
    {
        $messages = $this->getDoctrine()->getRepository(Ticket::class)
            ->findBy(['token' => $ticket]);

        if (empty($messages)) {
            throw new ArrayException(['ticket' => 'Ta wartość jest nieprawidłowa.']);
        }

        return new JsonResponse(['messages' => $messages]);
    }
}
