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
    public function getTicket(Request $request): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(self::REPOSITORY_CLASS);
        $token = $request->request->get(self::FIND_BY);

        return new JsonResponse($token ? $repository->find($token) : $repository->getListGroup());
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

        /** @var Ticket $ticket */
        $ticket = $repository->find($request->request->get('id'));
        $responseTicket = new Ticket();

        $service->setFieldsOfTickets($request, $responseTicket, $ticket);

        $repository->insert($responseTicket);
        $repository->update($ticket);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
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
