<?php

namespace MNGame\Service\Connection\Payment\Client;

use MNGame\Exception\ContentException;
use MNGame\Service\EnvironmentService;
use GuzzleHttp\Exception\GuzzleException;
use Doctrine\Common\Collections\Collection;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Service\Connection\ApiClient\RestApiClient;

class MicroSmsPaymentClient extends RestApiClient implements PaymentClientInterface
{
    private const URL = 'https://microsms.pl/api/v2/multi.php?';
    private SMSPriceRepository $smsPriceRepository;
    private Collection $paymentConfiguration;

    public function __construct(
        SMSPriceRepository $smsPriceRepository,
        Collection $paymentConfiguration,
        EnvironmentService $env,
        ?string $className = null
    ) {
        parent::__construct($env, $className);
        $this->paymentConfiguration = $paymentConfiguration;
        $this->smsPriceRepository   = $smsPriceRepository;
    }

    /**
     * @throws ContentException
     * @throws GuzzleException
     */
    public function executeRequest(array $data): ?float
    {
        $request = [
            'userid'    => $this->paymentConfiguration->get('userId'),
            'serviceid' => $this->paymentConfiguration->get('serviceId'),
            'code'      => $data['paymentId'],
        ];

        $response = json_decode($this->request(RestApiClient::GET, self::URL . http_build_query($request)), true);
        $this->handleError($response);

        return $this->smsPriceRepository->findOneBy(['id' => $response['data']['number']])->getAmount();
    }

    /**
     * @throws ContentException
     */
    protected function handleError(array $response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }
        if (!is_array($response)) {
            throw new ContentException(['error' => 'Nie można odczytać informacji o płatności.']);
        }
        if (isset($response['error']) && $response['error']) {
            throw new ContentException(['error' => 'Kod błędu: ' . $response['error']['errorCode'] . ' - ' . $response['error']['message']]);
        }
        if ((bool)$response['connect'] === false) {
            throw new ContentException(['smsCode' => 'Nieprawidłowy format kodu sms.']);
        }
        if (isset($errormsg) || !isset($response['connect']) || $response['data']['status'] != 1) {
            throw new ContentException(['smsCode' => 'Przesłany kod jest nieprawidłowy.']);
        }
    }
}
