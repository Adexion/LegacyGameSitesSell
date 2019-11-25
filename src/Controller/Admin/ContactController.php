<?php

namespace ModernGame\Controller\Admin;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Service\Content\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class ContactController extends AbstractController
{
    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function deleteTicket(Request $request)
    {
        /** @var TicketRepository $contactRepository */
        $contactRepository = $this->getDoctrine()->getRepository(Ticket::class);
        $contactRepository->delete($request->request->getInt('id'));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function putTicket(Request $request, TicketService $contact)
    {
        $contactEntity = $contact->mapEntity($request);

        $this->getDoctrine()->getRepository(Ticket::class)->insert($contactEntity);

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @SWG\Tag(name="Admin/Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function getTicket(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $id = $request->query->getInt('id');

        return new JsonResponse(empty($id) ? $repository->findAll() : $repository->find($id));
    }
}
