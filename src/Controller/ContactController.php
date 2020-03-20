<?php

namespace ModernGame\Controller;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Exception\ContentException;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\Content\TicketService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * Add new ticket from contact form
     *
     * Creating of new ticket. If you are logged in then ticket will be assigned to your account.
     *
     * @SWG\Tag(name="Contact")
     * @SWG\Parameter(
     *     type="object",
     *     name="JSON",
     *     in="body",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              type="integer",
     *              property="contactId"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="name"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="email"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="type"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="subject"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="message"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="status"
     *          ),
     *          @SWG\Property(
     *              type="string",
     *              property="reCaptcha"
     *          )
     *     )
     * )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     * )
     */
    public function setMessageContact(Request $request, TicketService $service): JsonResponse
    {
        $contact = $service->mapEntity($request, $this->getUser());
        $this->getDoctrine()->getRepository(Ticket::class)->insert($contact);

        return new JsonResponse(['token' => $contact->getToken()]);
    }

    /**
     * List of Tickets for specific token
     *
     * Response look like Ticket
     *
     * @SWG\Tag(name="Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  property="contactId"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="name"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="email"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="type"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="subject"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="message"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="status"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="reCaptcha"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="token"
     *              )
     *          )
     *     )
     * )
     */
    function getMessagesTicket(Request $request, CustomSerializer $serializer): JsonResponse
    {
        /** @var Ticket[] $messages */
        $messages = $this->getDoctrine()->getRepository(Ticket::class)
            ->findBy(['token' => $request->query->get('token')]);

        if (empty($messages)) {
            throw new ContentException(['token' => 'Ta wartość jest nieprawidłowa.']);
        }

        return new JsonResponse($serializer
            ->serialize($messages,'json', ['ignored_attributes' => 'user'])
            ->toArray()
        );
    }

    /**
     * Return logged user's list of active/not deleted tokens
     *
     * @SWG\Tag(name="Contact")
     * @SWG\Response(
     *     response=200,
     *     description="Evertythig works",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              type="object",
     *              @SWG\Property(
     *                  type="string",
     *                  property="token"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="name"
     *              )
     *          )
     *     )
     * )
     */
    function getMyTickets(): JsonResponse
    {
        $response = $this->getDoctrine()->getRepository(Ticket::class)
            ->getListGroup($this->getUser());

        return new JsonResponse(['messages' => $response]);
    }
}
