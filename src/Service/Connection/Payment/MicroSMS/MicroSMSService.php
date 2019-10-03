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

    const URL = 'http://microsms.pl/api/v2/multi.php?';

    private $client;
    private $container;
    private $price;

    public function __construct(
        PaymentHistoryRepository $repository,
        TokenStorageInterface $tokenStorage,
        ContainerInterface $container,
        PriceRepository $price,
        RestApiClient $client
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
    public function executePayment($id, string $payer = null): string
    {
        $configuration = $this->container->getParameter('microSMS');

        $request = [
            'userid' => $configuration['userId'],
            'serviceid' => $configuration['serviceId'],
            'code' => $id
        ];

        $response = json_decode($this->client->request(RestApiClient::GET, self::URL . http_build_query($request)));
        $this->handleError($response);

        $amount = $this->price->findOneBy(['phoneNumber' => $response->data->number])->getAmount();
        $this->notePayment($amount);

        return $amount;
    }

    /**
     * @throws Exception
     */
    private function handleError($response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }
        if (!is_object($response)) {
            throw new ContentException(['error' => 'Nie można odczytać informacji o płatności.']);
        }
        if (isset($response->error) && $response->error) {
            throw new ContentException(['error' => 'Kod błędu: ' . $response->error->errorCode . ' - ' . $response->error->message]);
        }
        if ((bool)$response->connect === false) {
            throw new ContentException(['smsCode' => 'Nieprawidłowy format kodu sms.']);
        }
        if (MicroSMSPredicate::isResponseValid($response)) {
            throw new ContentException(['smsCode' => 'Przesłany kod jest nieprawidłowy.']);
        }
    }
}
