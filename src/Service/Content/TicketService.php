<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Enum\TicketStatusEnum;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ResponseTicketType;
use ModernGame\Form\TicketType;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\AbstractService;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TicketService extends AbstractService
{
    private object $user;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TicketRepository $contactRepository,
        CustomSerializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $contactRepository;

        parent::__construct($form, $formErrorHandler, $contactRepository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request, ?UserInterface $user)
    {
        $message = $request->request->get('message');
        $name = $request->request->get('name');

        $request->request->set('status', TicketStatusEnum::NOT_READ);
        $request->request->set('token', md5(uniqid() . date('Y-m-d H:i') . $message . $name));

        if ($user instanceof User) {
            $request->request->set('user', $user->getId());
        }

        $contact = $this->map($request, new Ticket(), TicketType::class);

        return $contact;
    }

    /**
     * @throws ContentException
     */
    public function setFieldsOfTickets(Request $request, Ticket $responseTicket, Ticket $ticket)
    {
        $ticket->setStatus(TicketStatusEnum::ASSIGN_AS_READ);

        $request->request->replace($this->serializer->mergeDataWithEntity($ticket, $request->request->all()));

        $form = $this->form->create(ResponseTicketType::class, $responseTicket);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);
    }

    public function buildResponse($tokenList)
    {
        if (!isset($tokenList[0]) || !($tokenList[0] instanceof Ticket)) {
            return $tokenList;
        }

        return $this->serializer->serialize($tokenList, 'json', ['ignored_attributes' => ['user', 'reCaptcha']])
            ->toArray();
    }
}
