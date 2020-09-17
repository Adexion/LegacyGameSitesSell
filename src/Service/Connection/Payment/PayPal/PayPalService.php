<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Connection\Payment\AbstractPayment;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PayPalService extends AbstractPayment implements PaymentInterface
{
    private PaypalClient $paypalClient;
    private ContainerInterface $container;

    public function __construct(
        PaymentHistoryRepository $repository,
        ContainerInterface $container,
        PaypalClient $paypalClient,
        UserProviderInterface $userProvider
    ) {
        $this->paypalClient = $paypalClient;
        $this->container = $container;

        parent::__construct($repository, $userProvider);
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    public function executePayment(string $id, string $username): float
    {
        $configuration = $this->container->getParameter('paypal');
        $token = $this->paypalClient->tokenRequest($configuration['client'], $configuration['secret'])['access_token'] ?? '';
        $response = $this->paypalClient->executeRequest($token, $id);

        $amount = $response['purchase_units'][0]['amount']['value'];

        $this->notePayment($amount, $username, 'paypal', $id);

        return (float)$amount;
    }
}
