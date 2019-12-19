<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PaymentHistoryRepository;
use ModernGame\Database\Repository\PriceRepository;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;
use ModernGame\Service\Connection\Payment\AbstractPayment;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MicroSMSService extends AbstractPayment implements PaymentInterface
{
    private MicroSMSClient $client;
    private ContainerInterface $container;
    private PriceRepository $price;

    public function __construct(
        PaymentHistoryRepository $repository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        PriceRepository $price,
        MicroSMSClient $client
    ) {
        $this->client = $client;
        $this->container = $container;
        $this->price = $price;

        parent::__construct($repository, $tokenStorage);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function executePayment($id, $payer = null): float
    {
        $configuration = $this->container->getParameter('microSMS');

        $response = $this->client->executeRequest($configuration['userId'], $configuration['serviceId'], $id);

        $amount = $this->price->findOneBy(['phoneNumber' => $response['data']['number']])->getAmount();
        $this->notePayment($amount);

        return (float)$amount;
    }
}
