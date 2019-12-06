<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Entity\Token;
use ModernGame\Enum\TicketStatusEnum;
use ModernGame\Form\ResponseTicketType;
use ModernGame\Form\TicketType;
use ModernGame\Service\Content\TicketService;
use ModernGame\Validator\FormErrorHandler;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Ticket::class;
    protected const FIND_BY = 'token';

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Parameter(
     *     name="ticket",
     *     in="query",
     *     type="string",
     *     required=false
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getEntity(Request $request): JsonResponse
    {
        return parent::getEntity($request);
    }

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Parameter(
     *     name="JSON",
     *     in="body",
     *     type="object",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="integer"),
     *          @SWG\Property(property="message", type="string"),
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postTicket(Request $request, TicketService $service, FormErrorHandler $handler): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(self::REPOSITORY_CLASS);

        $ticket = $repository->find($request->request->get('id'));
        $responseTicket = new Ticket();

        $form = $this->createForm(ResponseTicketType::class, $responseTicket);

        $responseTicket->setName($ticket->getName());
        $responseTicket->setEmail($ticket->getEmail());
        $responseTicket->setType($ticket->getType());
        $responseTicket->setSubject($ticket->getSubject());
        $responseTicket->setToken($ticket->getToken());

        $responseTicket->setStatus(TicketStatusEnum::ASSIGN_AS_READ);
        $ticket->setStatus(TicketStatusEnum::ASSIGN_AS_READ);

        $repository->insert($responseTicket);
        $repository->update($ticket);

        $form->handleRequest($request);
        $handler->handle($form);
    }

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteEntity(Request $request): JsonResponse
    {
        return parent::deleteEntity($request);
    }
}
