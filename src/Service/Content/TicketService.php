<?php

namespace ModernGame\Service\Content;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException as ORMExceptionAlias;
use ModernGame\Database\Entity\Ticket;
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
        $message = $request->request->get('message');
        $name = $request->request->get('name');

        $request->request->set('status', TicketStatusEnum::NOT_READ);
        $request->request->set('token', md5(uniqid() . date('Y-m-d H:i') . $message . $name));

        $contact = $this->map($request, new Ticket(), TicketType::class);

        return $contact;
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

    public function mapEntityById(Request $request) {}
}
