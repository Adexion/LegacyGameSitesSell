<?php

namespace MNGame\EventListener;

use MNGame\Exception\ContentException;
use MNGame\Exception\PaymentProcessingException;
use MNGame\Service\EnvironmentService;
use MNGame\Service\Mail\MailSenderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Throwable;

class ExceptionListener
{
    public const ERROR = 404;

    private LoggerInterface $logger;
    private MailSenderService $service;
    private EnvironmentService $env;

    public function __construct(LoggerInterface $logger, MailSenderService $service, EnvironmentService $env)
    {
        $this->logger = $logger;
        $this->service = $service;
        $this->env = $env;
    }

    /**
     * @throws Throwable
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getStatusCode() === Response::HTTP_NOT_FOUND) {
                $response = new RedirectResponse('/');
                $event->setResponse($response);

                return;
            }

            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->setContent(json_encode(['error' => $exception->getMessage()]));
        } elseif ($this->isClientSideError($exception)) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());
        } else {
            $this->logger->critical($exception);

            if ($this->env->isProd()) {
                $this->service->sendEmailBySchema(
                    self::ERROR,
                    $exception->getMessage().' '.date('Y-m-d H:i:s')
                );
            } elseif ($this->env->isTest() || $this->env->isDev()) {
                throw $exception;
            }

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }

    private function isClientSideError(Throwable $e): bool
    {
        return $e instanceof ContentException
            || $e instanceof BadCredentialsException
            || $e instanceof PaymentProcessingException;
    }
}
