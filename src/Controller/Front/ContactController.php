<?php

namespace MNGame\Controller\Front;

use Exception;
use Doctrine\ORM\ORMException;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\User;
use MNGame\Form\ContactTicketType;
use MNGame\Service\ContactService;
use MNGame\Util\ViewVersionProvider;
use Doctrine\ORM\OptimisticLockException;
use MNGame\Service\Mail\MailSenderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MNGame\Service\Content\Parameter\ParameterProvider;

class ContactController extends AbstractController
{
    /**
     * @Route(name="contact", path="/contact")
     *
     * @throws Exception
     */
    public function frontContact(Request $request, ContactService $contactService): Response
    {
        return $this->render('base/page/contact.html.twig', $contactService->getContactForm($request));
    }
}
