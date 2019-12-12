<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Content\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

class ContactController extends AbstractController
{
    /**
     * @SWG\Tag(name="Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function setMessageContact(Request $request, TicketService $service)
    {
        $contact = $service->mapEntity($request);
        $this->getDoctrine()->getRepository(Ticket::class)->insert($contact);

        return new JsonResponse(['token' => $contact->getToken()]);
    }

    /**
     * @SWG\Tag(name="Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    function getMessagesTicket(Request $request)
    {
        $messages = $this->getDoctrine()->getRepository(Ticket::class)
            ->findBy(['token' => $request->request->get('token')]);

        if (empty($messages)) {
            throw new ContentException(['token' => 'Ta wartość jest nieprawidłowa.']);
        }

        return new JsonResponse(['messages' => $messages]);
    }

    /**
     * @SWG\Tag(name="Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    function getMyTickets(Request $request)
    {
        $response = $this->getDoctrine()->getRepository(Ticket::class)
            ->findBy(['userId' => $this->getUser()->getId()]);

        return new JsonResponse(['messages' => $response]);
    }
}
