<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Service\Content\TicketService;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractAdminController
{
    protected const REPOSITORY_CLASS = Ticket::class;
    protected const FIND_BY = 'token';

    //ToDo: Add method for responding to user. How can i forgot?!

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
    public function putTicket(Request $request, TicketService $contact): JsonResponse
    {
        return $this->putEntity($request, $contact);
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
