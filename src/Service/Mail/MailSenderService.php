<?php

namespace ModernGame\Service\Mail;

use Swift_Mailer;
use Swift_Message;

class MailSenderService
{
    private $mailer;
    private $provider;

    public function __construct(Swift_Mailer $mailer, SchemaListProvider $provider)
    {
        $this->mailer = $mailer;
        $this->provider = $provider;
    }

    public function sendEmail($schemaId, string $data, string $email)
    {
        $schema = $this->provider->provide($schemaId);

        $body = str_replace($schema['replace'], $data, $schema['text']);

        $message = (new Swift_Message($schema['title']))
            ->setFrom('moderngameservice@gmail.com')
            ->setTo($email)
            ->setBody($body,'text/html');

        $this->mailer->send($message);
    }
}
