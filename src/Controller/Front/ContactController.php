<?php

namespace MNGame\Controller\Front;

use Exception;
use MNGame\Database\Entity\Ticket;
use MNGame\Database\Entity\User;
use MNGame\Form\ContactTicketType;
use MNGame\Service\Mail\MailSenderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route(name="contact", path="/contact")
     *
     * @throws Exception
     */
    public function contact(Request $request, MailSenderService $service): Response
    {
        $form = $this->createForm(ContactTicketType::class);

        $message = $request->request->get('message');
        $name = $request->request->get('name');
        $request->request->set('token', hash('sha256', uniqid().date('Y-m-d H:i').$message.$name));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Ticket $ticket */
            $ticket = $form->getData();
            $ticket->setDatetime();

            $user = $this->getUser();
            if ($user instanceof User) {
                $ticket->setUser($user);
            }

            $this->getDoctrine()->getRepository(Ticket::class)->insert($ticket);
            $service->sendEmailBySchema('contact', [$ticket->getEmail(), $ticket->getMessage()]);

            return $this->render('base/page/contact.html.twig', [
                'message' => 'Wiadomość wysłana. Postaramy się odpowiedzieć w przeciągu 24h. Dziękujemy!',
                'contact_form' => $form->createView(),
            ]);
        }

        return $this->render('base/page/contact.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
}
