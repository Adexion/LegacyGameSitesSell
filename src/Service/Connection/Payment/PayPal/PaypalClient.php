<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;

class PaypalClient extends RestApiClient
{
    private const API_EXECUTE = '/v1/payments/payment/%s/execute';
    private const API_TOKEN = '/v1/oauth2/token';

    private const PAYPAL_API = 'https://api.paypal.com';

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function tokenRequest(string $client, string $secret): array
    {
        $request['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
            'Authorization' => 'Basic ' . base64_encode($client . ':' . $secret)
        ];

        $request['body'] = [
            'grant_type' => 'client_credentials'
        ];

        return json_decode($this->request(self::POST, self::PAYPAL_API . self::API_TOKEN, $request), true) ?? [];
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function executeRequest($token, $paymentId, $payerId): array
    {
        $request['headers'] = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $request['body'] = json_encode([
            'payer_id' => $payerId
        ]);

        $response = json_decode($this->request(
                self::POST,
                self::PAYPAL_API . sprintf(self::API_EXECUTE, $paymentId),
                $request
            ), true) ?? [];

        $this->handleError($response);

        return $response;
    }

    /**
     * @throws ContentException
     */
    protected function handleError(array $response)
    {
        if (!($response['transactions'][0]['amount']['total'] ?? false)) {
            throw new ContentException(['paymentId' => 'Podana płatność nie istnieje lub wystąpił problem po stronie serwera.']);
        }
    }
}
