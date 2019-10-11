<?php

namespace ModernGame\Service\Content;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException as ORMExceptionAlias;
use ModernGame\Database\Entity\Ticket;
use ModernGame\Database\Repository\TicketRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Form\ContactType;
use ModernGame\Form\TicketType;
use ModernGame\Service\AbstractService;
use ModernGame\Serializer\CustomSerializer;
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
    public function mapEntity(Request $request)
    {
        $contact = $this->map($request, new Ticket(), ContactType::class);

        $contact->setStatus(1);
        $contact->setToken(md5(uniqid() . date('Y-m-d H:i') . $contact->getMessage() . $contact->getName()));

        return $contact;
    }

    /**
     * @throws ContentException
     * @throws ORMExceptionAlias
     * @throws OptimisticLockException
     */
    public function mapEntityById(Request $request)
    {
        $ticket =  $this->mapById($request, TicketType::class);
        $this->assignAsRead($request->request->getInt('id'));

        return $ticket;
    }

    /**
     * @throws ORMExceptionAlias
     * @throws OptimisticLockException
     */
    private function assignAsRead(int $id)
    {
        /** @var Ticket $contactEntity */
        $contactEntity = $this->repository->find($id);
        $contactEntity->setStatus(2);

        $this->repository->update($contactEntity);
    }
}
