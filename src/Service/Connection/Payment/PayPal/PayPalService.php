<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Connection\Payment\AbstractPayment;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PayPalService extends AbstractPayment implements PaymentInterface
{
    private PaypalClient $client;
    private ContainerInterface $container;

    public function __construct(
        PaymentHistoryRepository $repository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        PaypalClient $client
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
    public function executePayment($id, $payer = null, bool $notePayment = true): float
    {
        $configuration = $this->container->getParameter('paypal');

        $token = $this->client->tokenRequest($configuration['client'], $configuration['secret'])['access_token'] ?? '';
        $response = $this->client->executeRequest($token, $id, $payer);

        $amount = $response['transactions'][0]['amount']['total'];
        $this->notePayment($amount);

        return (float)$amount;
    }
}
