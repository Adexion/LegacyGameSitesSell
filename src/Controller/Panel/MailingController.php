<?php

namespace MNGame\Controller\Panel;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use MNGame\Database\Entity\User;
use MNGame\Exception\ContentException;
use MNGame\Form\MailingType;
use MNGame\Service\Connection\Client\ClientFactory;
use MNGame\Service\Mail\MailSenderService;
use MNGame\Service\ServerProvider;
use ReflectionClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailingController extends AbstractDashboardController implements DashboardControllerInterface
{
    use MainDashboardController;

    /**
     * @Route("/panel/mailing", name="mailing")
     */
    public function main(Request $request, MailSenderService $mailSenderService): Response
    {
        $form = $this->createForm(MailingType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           return $this->sendMail($form, $mailSenderService);
        }

        return $this->render('@MNGame/panel/mail.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'form' => $form->createView(),
        ]);
    }

    private function sendMail(FormInterface $form, MailSenderService $mailSendService): Response
    {
        $data = $form->getData();
        $title = $data['title'];
        $content = $data['content'];

        /** @var User $user */
        foreach ($data['userList'] as $user) {
            $replacement = [
                "%email%" => $user->getEmail(),
                "%username%" => $user->getUsername()
            ];

            $mailSendService->sendPublicEmail(
                $title,
                $content,
                array_values($replacement),
                array_keys($replacement),
                $user->getEmail()
            );
        }

        return $this->render('@MNGame/panel/mail.html.twig', [
            'dashboard_controller_filepath' => (new ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new ReflectionClass(static::class))->getShortName(),
            'mailSent' => true,
            'form' => $form->createView(),
        ]);
    }
}
