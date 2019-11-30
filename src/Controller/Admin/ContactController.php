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

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function postTicket(Request $request, TicketService $contact): JsonResponse
    {
        return $this->postEntity($request, $contact);
    }

    /**
     * @SWG\Tag(name="Admin/Contact")
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
