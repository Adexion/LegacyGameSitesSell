<?php

namespace MNGame\Service\Connection\Payment\MicroSMS;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Database\Repository\PaymentHistoryRepository;
use MNGame\Database\Repository\PriceRepository;
use MNGame\Service\Connection\Payment\AbstractPayment;
use MNGame\Service\Connection\Payment\PaymentInterface;
use MNGame\Service\ServerProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MicroSMSService extends AbstractPayment implements PaymentInterface
{
    private MicroSMSClient $microSMSClient;
    private PriceRepository $price;
    private array $microSMSData;

    public function __construct(
        PaymentHistoryRepository $repository,
        PriceRepository $price,
        MicroSMSClient $microSMSClient,
        UserProviderInterface $userProvider,
        ServerProvider $serverProvider
    ) {
        $this->microSMSData = $serverProvider->getSessionServer()->getMicroSMS()->toArray();
        $this->microSMSClient = $microSMSClient;
        $this->price = $price;

        parent::__construct($repository, $userProvider);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function executePayment(string $id, string $username): float
    {
        $response = $this->microSMSClient->executeRequest($this->microSMSData['userId'], $this->microSMSData['serviceId'], $id);

        $amount = $this->price->findOneBy(['phoneNumber' => $response['data']['number']])->getAmount();
        $this->notePayment($amount, $username, 'sms', $id);

        return (float)$amount;
    }
}
