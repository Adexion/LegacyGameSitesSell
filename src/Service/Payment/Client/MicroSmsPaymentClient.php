<?php

namespace MNGame\Service\Payment\Client;

use MNGame\Exception\ContentException;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Service\ApiClient\RestApiClient;
use MNGame\Exception\PaymentProcessingException;

class MicroSmsPaymentClient extends DefaultPaymentClient implements PaymentClientInterface
{
    private const URL = 'https://microsms.pl/api/v2/multi.php?';

    /**
     * @throws ContentException
     * @throws GuzzleException
     * @throws PaymentProcessingException
     */
    public function executeRequest(array $data): ?string
    {
        $paymentId = parent::executeRequest($data);

        $request = [
            'userid'    => $this->paymentConfiguration->get('userId'),
            'serviceid' => $this->paymentConfiguration->get('serviceId'),
            'code'      => $data['paymentId'],
        ];

        $response = json_decode($this->request(RestApiClient::GET, self::URL . http_build_query($request)), true);
        $this->handleError($response);

        return $paymentId;
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
