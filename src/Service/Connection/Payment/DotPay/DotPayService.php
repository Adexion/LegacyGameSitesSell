<?php

namespace ModernGame\Service\Connection\Payment\DotPay;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Connection\Payment\AbstractPayment;
use ModernGame\Service\Connection\Payment\PaymentInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DotPayService extends AbstractPayment implements PaymentInterface
{
    private const PAYMENT_COMPLETED = 'completed';
    private const PAYMENT_REJECTED = 'rejected';

    private $client;
    private $container;

    public function __construct(
        PaymentHistoryRepository $repository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        DotPayClient $client
    ) {
        $this->client = $client;
        $this->container = $container;

        parent::__construct($repository, $tokenStorage);
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    public function executePayment($id, $payer = null, $notePayment = true): float
    {
        $configuration = $this->container->getParameter('dotPay');

        $response = $this->client->executeRequest($configuration['username'], $configuration['password'], $id);

        $this->handleException($response);
        $this->notePayment((float)$response['amount']);

        return (float)$response['amount'];
    }

    /**
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    private function handleException(array $response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }

        if (isset($response['details'])) {
            throw new ContentException(['error' => 'Podana płatność nie istnieje']);
        }

        if($response['status'] === self::PAYMENT_REJECTED)
        {
            throw new ContentException(['error' => 'Twoja płatność została odrzucona']);
        }

        if ($response['status'] !== self::PAYMENT_COMPLETED) {
            throw new PaymentProcessingException('Płatność oczekuje na potwierzenie wykonania');
        }
    }
}
