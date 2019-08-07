<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PayPalService implements PaymentInterface
{
    private $client;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->client = new PaypalClient();
        $this->container = $container;
    }

    /**
     * @throws GuzzleException
     */
    public function executePayment(string $id, string $payer = null): string
    {
        $configuration = $this->container->getParameter('paypal');

        $token = $this->client->tokenRequest($configuration['client'], $configuration['secret'])['access_token'];

        return  $this->client->executeRequest($token, $id, $payer)['transactions'][0]['amount']['total'];
    }
}
