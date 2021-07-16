<?php

namespace MNGame\Service\Payment\Client;

use MNGame\Exception\ContentException;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Service\ApiClient\RestApiClient;
use MNGame\Exception\PaymentProcessingException;

class HotPaySmsPaymentClient extends DefaultPaymentClient implements PaymentClientInterface
{
    private const URL = 'https://api.hotpay.pl/check_sms.php?';

    /**
     * @throws ContentException
     * @throws GuzzleException
     * @throws PaymentProcessingException
     */
    public function executeRequest(array $data): ?string
    {
        $paymentId = parent::executeRequest($data);

        $request = [
            'secret' => $this->paymentConfiguration->get('secret'),
            'code'   => $data['paymentId'],
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

        if (isset($response['tresc'])) {
            throw new ContentException(['error' => $response['tresc']]);
        }

        if (isset($response['aktywacja']) && (int)$response['aktywacja'] > 1) {
            throw new ContentException(['smsCode' => 'Przesłany kod jest nieprawidłowy.']);
        }
    }
}
