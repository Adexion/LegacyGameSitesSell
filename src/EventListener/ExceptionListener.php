<?php

namespace ModernGame\EventListener;

use ModernGame\Exception\ArrayException;
use ModernGame\Service\Mail\MailSenderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    const ERROR = 404;

    private $logger;
    private $service;

    public function __construct(LoggerInterface $logger, MailSenderService $service)
    {
        $this->logger = $logger;
        $this->service = $service;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new Response();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else if ($exception instanceof ArrayException) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());
        } else {
            $this->logger->critical($exception);
            $this->service->sendEmail(
                self::ERROR,
                $exception->getMessage() . ' ' . date('Y-m-d H:i:s'),
                'moderngameservice@gmail.com'
            );

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
