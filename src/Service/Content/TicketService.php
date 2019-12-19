<?php

namespace ModernGame\Service\Content;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException as ORMExceptionAlias;
use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Entity\User;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Enum\TicketStatusEnum;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ResponseTicketType;
use ModernGame\Form\TicketType;
use ModernGame\Serializer\CustomSerializer;
use ModernGame\Service\AbstractService;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TicketService extends AbstractService implements ServiceInterface
{
    private $user;

    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TicketRepository $contactRepository,
        TokenStorageInterface $tokenStorage,
        CustomSerializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $contactRepository;
        $this->user = $tokenStorage->getToken()->getUser();

        parent::__construct($form, $formErrorHandler, $contactRepository, $serializer);
    }

    /**
     * @throws ContentException
     */
    public function mapEntity(Request $request)
    {
        $message = $request->request->get('message');
        $name = $request->request->get('name');

        $request->request->set('status', TicketStatusEnum::NOT_READ);
        $request->request->set('token', md5(uniqid() . date('Y-m-d H:i') . $message . $name));

        if ($this->user instanceof User) {
            $request->request->set('user', $this->user->getId());
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

    public function mapEntityById(Request $request)
    {
    }

    /**
     * @throws ORMExceptionAlias
     * @throws OptimisticLockException
     */
    private function assignAsRead(int $id)
    {
        /** @var Ticket $contactEntity */
        $contactEntity = $this->repository->find($id);
        $contactEntity->setStatus(TicketStatusEnum::ASSIGN_AS_READ);

        $this->repository->update($contactEntity);
    }
}
