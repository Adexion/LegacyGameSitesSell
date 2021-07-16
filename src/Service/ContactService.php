<?php

namespace MNGame\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\User;
use MNGame\Form\ContactTicketType;
use MNGame\Service\Mail\MailSenderService;
use Stringable;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactService
{
    private FormFactoryInterface $formFactory;
    private EntityManagerInterface $entityManager;
    private MailSenderService $mailSenderService;
    private string|Stringable|UserInterface $user;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, MailSenderService $mailSenderService, TokenStorageInterface $tokenStorage)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->mailSenderService = $mailSenderService;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getContactForm(Request $request): array
    {
        $form = $this->formFactory->create(ContactTicketType::class);

        $message = $request->request->get('message');
        $name = $request->request->get('name');
        $request->request->set('token', hash('sha256', uniqid().date('Y-m-d H:i').$message.$name));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Ticket $ticket */
            $ticket = $form->getData();
            $ticket->setDatetime();

            if ($this->user instanceof User) {
                $ticket->setUser($this->user);
            }

            $this->entityManager->getRepository(Ticket::class)->insert($ticket);
            $this->mailSenderService->sendEmailBySchema('contact', [$ticket->getEmail(), $ticket->getMessage()]);

            return [
                'message' => 'Wiadomość wysłana. Postaramy się odpowiedzieć w przeciągu 24h. Dziękujemy!',
                'contact_form' => $form->createView(),
            ];
        }

        return [
            'contact_form' => $form->createView(),
        ];
    }
}