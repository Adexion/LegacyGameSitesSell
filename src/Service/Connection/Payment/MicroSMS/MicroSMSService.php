<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Database\Repository\PriceRepository;
use ModernGame\Service\Connection\Payment\AbstractPayment;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MicroSMSService extends AbstractPayment implements PaymentInterface
{
    private MicroSMSClient $microSMSClient;
    private ContainerInterface $container;
    private PriceRepository $price;

    public function __construct(
        PaymentHistoryRepository $repository,
        ContainerInterface $container,
        PriceRepository $price,
        MicroSMSClient $microSMSClient,
        UserProviderInterface $userProvider
    ) {
        $this->microSMSClient = $microSMSClient;
        $this->container = $container;
        $this->price = $price;

        parent::__construct($repository, $userProvider);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function executePayment(string $id, string $username): float
    {
        $configuration = $this->container->getParameter('microSMS');
        $response = $this->microSMSClient->executeRequest($configuration['userId'], $configuration['serviceId'], $id);

        $amount = $this->price->findOneBy(['phoneNumber' => $response['data']['number']])->getAmount();
        $this->notePayment($amount, $username);

        return (float)$amount;
    }
}
