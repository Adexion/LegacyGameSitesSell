<?php

namespace ModernGame\Service;

use Swift_Mailer;
use Swift_Message;

class MailSenderService
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($schemaId, string $data, string $email)
    {
        $schema = [];

        $body = str_replace($schema['replace'], $data, $schema['text']);

        $message = (new Swift_Message($schema['title']))
            ->setFrom('moderngameservice@gmail.com')
            ->setTo($email)
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }
}
