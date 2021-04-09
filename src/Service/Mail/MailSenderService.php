<?php

namespace MNGame\Service\Mail;

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

    public function sendEmailBySchema(string $schemaId, $data, string $email = 'moderngameservice@gmail.com'): int
    {
        $schema = $this->provider->provide($schemaId);

        $body = str_replace($schema['replace'], $data, $schema['text']);
        $message = (new Swift_Message($schema['title']))
            ->setFrom('moderngameservice@gmail.com')
            ->setTo($email)
            ->setBody($body,'text/html');

        return $this->mailer->send($message);
    }

    public function sendPublicEmail(string $tittle, string $content, $data, $replacement, string $email): int
    {
        $body = str_replace($replacement, $data, $content);
        $message = (new Swift_Message($tittle))
            ->setFrom('moderngameservice@gmail.com')
            ->setTo($email)
            ->setBody($body,'text/html');

        return $this->mailer->send($message);
    }
}
