<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\Contact;
use ModernGame\Exception\ArrayException;
use ModernGame\Service\Content\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    public function setMessageContact(Request $request, ContactService $contact)
    {
        return new JsonResponse(['ticket' => $contact->setContactMessage($request)]);
    }

    function getMessagesTicket(string $ticket)
    {
        $messages = $this->getDoctrine()->getRepository(Contact::class)
            ->findBy(['token' => $ticket]);

        if (empty($messages)) {
            throw new ArrayException(['ticket' => 'Ta wartość jest nieprawidłowa.']);
        }

        return new JsonResponse(['messages' => $messages]);
    }
}
