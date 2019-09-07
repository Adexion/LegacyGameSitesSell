<?php

namespace ModernGame\Service\Content;

use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ContactType;
use ModernGame\Form\TicketType;
use ModernGame\Service\AbstractService;
use ModernGame\Service\Serializer;
use ModernGame\Service\ServiceInterface;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class TicketService extends AbstractService implements ServiceInterface
{
    public function __construct(
        FormFactoryInterface $form,
        FormErrorHandler $formErrorHandler,
        TicketRepository $contactRepository,
        Serializer $serializer
    ) {
        $this->form = $form;
        $this->formErrorHandler = $formErrorHandler;
        $this->repository = $contactRepository;

        parent::__construct($form, $formErrorHandler, $contactRepository, $serializer);
    }

    /**
     * @throws ArrayException
     */
    public function mapEntity(Request $request)
    {
        $contact = $this->map($request, new Ticket(), ContactType::class);

        $contact->setStatus(1);
        $contact->setToken(md5(uniqid() . date('Y-m-d H:i') . $contact->getMessage() . $contact->getName()));

        return $contact;
    }

    /**
     * @throws ArrayException
     */
    public function mapEntityById(Request $request)
    {
        return $this->mapById($request, TicketType::class);
    }

// ToDo: use when admin ready
//    /**
//     * @throws ORMException
//     * @throws OptimisticLockException
//     */
//    public function assignAsRead(Request $request)
//    {
//        /** @var Ticket $contactEntity */
//        $contactEntity = $this->repository->find($request->query->get('id'));
//        $contactEntity->setStatus(2);
//        $this->repository->update($contactEntity);
//    }
}
