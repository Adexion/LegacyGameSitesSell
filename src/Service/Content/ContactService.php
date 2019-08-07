<?php

namespace ModernGame\Service\Content;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ModernGame\Database\Entity\Contact;
use ModernGame\Database\Repository\ContactRepository;
use ModernGame\Exception\ArrayException;
use ModernGame\Form\ContactType;
use ModernGame\Form\TicketType;
use ModernGame\Validator\FormErrorHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactService
{
    private $form;
    private $formErrorHandler;
    private $contactRepository;
    private $validator;

    public function __construct(
        FormFactoryInterface $form,
        ValidatorInterface $validator,
        FormErrorHandler $formErrorHandler,
        ContactRepository $contactRepository
    ) {
        $this->form = $form;
        $this->validator = $validator;
        $this->formErrorHandler = $formErrorHandler;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @throws ArrayException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function setContactMessage(Request $request)
    {
        $contact = new Contact();
        $form = $this->form->create(ContactType::class, $contact);

        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        $contact->setStatus(1);
        $contact->setToken(md5(uniqid() . date('Y-m-d H:i') . $contact->getMessage() . $contact->getName()));
        $this->contactRepository->insert($contact);

        return $contact->getToken();
    }

    /**
     * @throws ArrayException
     */
    public function getMappedTicket(Request $request)
    {
        $contact = new Contact();

        $form = $this->form->create(TicketType::class, $contact);
        $form->handleRequest($request);
        $this->formErrorHandler->handle($form);

        return $contact;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function assignAsRead(Request $request)
    {
        /** @var Contact $contactEntity */
        $contactEntity = $this->contactRepository->find($request->query->get('id'));
        $contactEntity->setStatus(2);

        $this->contactRepository->update($contactEntity);
    }
}
