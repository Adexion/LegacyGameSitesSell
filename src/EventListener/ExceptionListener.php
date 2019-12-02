<?php

namespace ModernGame\EventListener;

use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\EnvironmentService;
use ModernGame\Service\Mail\MailSenderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Throwable;

class ExceptionListener
{
    const ERROR = 404;

    private $logger;
    private $service;
    private $env;

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
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->setContent(json_encode(['error' => $exception->getMessage()]));
        } else {
            if ($this->isClientSideError($exception)) {
                $response->setContent($exception->getMessage());
                $response->setStatusCode($exception->getCode());
            } else {
                $this->logger->critical($exception);

                if ($this->env->isProd()) {
                    $this->service->sendEmail(
                        self::ERROR,
                        $exception->getMessage() . ' ' . date('Y-m-d H:i:s'),
                        'moderngameservice@gmail.com'
                    );
                } else {
                    if ($this->env->isTest() || $this->env->isDev()) {
                        throw $exception;
                    }
                }

                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $event->setResponse($response);
    }

    private function isClientSideError(Throwable $e)
    {
        return $e instanceof ContentException
            || $e instanceof BadCredentialsException
            || $e instanceof PaymentProcessingException;
    }
}
