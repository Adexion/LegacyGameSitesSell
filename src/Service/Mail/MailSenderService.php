<?php

namespace ModernGame\Service\Mail;

use Swift_Mailer;
use Swift_Message;

class MailSenderService
{
    private Swift_Mailer $mailer;
    private SchemaListProvider $provider;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->provider = new SchemaListProvider();
    }

    public function sendEmail($schemaId, $data, string $email)
    {
        $schema = $this->provider->provide($schemaId);

        $body = str_replace($schema['replace'], $data, $schema['text']);

        $message = (new Swift_Message($schema['title']))
            ->setFrom('moderngameservice@gmail.com')
            ->setTo($email)
            ->setBody($body,'text/html');

        return $this->mailer->send($message);
    }
}
