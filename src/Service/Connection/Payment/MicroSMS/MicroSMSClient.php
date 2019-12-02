<?php

namespace ModernGame\Service\Connection\Payment\MicroSMS;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;

class MicroSMSClient extends RestApiClient
{
    private const URL = 'http://microsms.pl/api/v2/multi.php?';

    /**
     * @throws ContentException
     * @throws GuzzleException
     */
    public function executeRequest(string $userId, string $serviceId, string $code) {
        $request = [
            'userid' => $userId,
            'serviceid' => $serviceId,
            'code' => $code
        ];

        $response = json_decode($this->request(RestApiClient::GET, self::URL . http_build_query($request)), true);
        $this->handleError($response);

    }

    /**
     * @throws ContentException
     */
    protected function handleError(array $response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }
        if (!is_object($response)) {
            throw new ContentException(['error' => 'Nie można odczytać informacji o płatności.']);
        }
        if (isset($response['error']) && $response['error']) {
            throw new ContentException(['error' => 'Kod błędu: ' . $response['error']['errorCode'] . ' - ' . $response['error']['message']]);
        }
        if ((bool)$response['connect'] === false) {
            throw new ContentException(['smsCode' => 'Nieprawidłowy format kodu sms.']);
        }
        if (MicroSMSPredicate::isResponseInvalid($response)) {
            throw new ContentException(['smsCode' => 'Przesłany kod jest nieprawidłowy.']);
        }
    }
}
