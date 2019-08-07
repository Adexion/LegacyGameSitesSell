<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Database\Repository\PriceRepository;
use ModernGame\Service\Connection\ApiClient\RestApiClient;
use ModernGame\Service\Connection\Payment\PaymentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MicroSMSService implements PaymentInterface
{
    const URL = 'http://microsms.pl/api/v2/multi.php';

    private $client;
    private $container;
    private $price;

    public function __construct(ContainerInterface $container, PriceRepository $price)
    {
        $this->client = new RestApiClient();
        $this->container = $container;
        $this->price = $price;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function executePayment(string $id, string $payer = null): string
    {
        $configuration = $this->container->getParameter('microSMS');

        $request['body'] = [
            'userid' => $configuration['userId'],
            'serviceid' => $configuration['serviceId'],
            'code' => $id
        ];

        $response = json_decode($this->client->request(RestApiClient::GET, self::URL, $request));
        $this->handleError($response);

        return $this->price->findOneBy(['phoneNumber' => $response->data->number]);
    }

    /**
     * @throws Exception
     */
    private function handleError($response)
    {
        if (empty($response)) {
            $message = 'Nie można nawiązać połączenia z serwerem płatności.';
        } else {
            $response = json_decode($response);

            if (!is_object($response)) {
                $message = 'Nie można odczytać informacji o płatności.';
            } else if (isset($response->error) && $response->error) {
                $message = 'Kod błędu: ' . $response->error->errorCode . ' - ' . $response->error->message;
            } else if ((bool)$response->connect === false) {
                $message = 'Kod błędu: ' . $response->data->errorCode . ' - ' . $response->data->message;
            }

            if (MicroSMSPredicate::isResponseValid($response)) {
                throw new Exception('Przesłany kod jest nieprawidłowy, spróbuj ponownie.');
            }
        }

        if (isset($message)) {
            throw new Exception($message);
        }
    }
}
