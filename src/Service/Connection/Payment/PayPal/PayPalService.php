<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PayPalService implements PaymentInterface
{
    private $client;
    private $container;

    public function __construct(ContainerInterface $container, PaypalClient $client)
    {
        $this->client = $client;
        $this->container = $container;
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function executePayment(string $id, string $payer = null): string
    {
        $configuration = $this->container->getParameter('paypal');

        $token = $this->client->tokenRequest($configuration['client'], $configuration['secret'])['access_token'];
        $response = $this->client->executeRequest($token, $id, $payer);

        if (!($response['transactions'][0]['amount']['total'] ?? false)) {
            throw new ContentException(['paymentID' => 'Podana płatność nie istnieje lub wystąpił problem po stronie serwera.']);
        }

        return $response['transactions'][0]['amount']['total'];
    }
}
