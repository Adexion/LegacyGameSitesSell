<?php

namespace ModernGame\Service\Connection\Payment\PayPal;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;

class PaypalClient extends RestApiClient
{
    private const API_EXECUTE = ' /v2/checkout/orders/%s';
    private const API_TOKEN = '/v1/oauth2/token';

    private const PAYPAL_API = 'https://api.paypal.com';
    private const PAYPAL_SANDBOX__API = 'https://api.sandbox.paypal.com';

    private const PAYMENT_COMPLETED = 'COMPLETED';

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

        return json_decode($this->request(self::POST, self::PAYPAL_SANDBOX__API . self::API_TOKEN, $request), true) ?? [];
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    public function executeRequest($token, $orderId): array
    {
        $request['headers'] = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $rawResponse = $this->request(
            self::GET,
            self::PAYPAL_SANDBOX__API . sprintf(self::API_EXECUTE, $orderId), $request
        );

        header('Access-Control-Allow-Origin: *');
        var_dump($rawResponse);die;

        $response = json_decode($rawResponse, true) ?? [];

        $this->handleError($response);

        return $response;
    }

    /**
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    protected function handleError(array $response)
    {
        if (isset($response['status']) && $response['status'] !== self::PAYMENT_COMPLETED) {
            throw new PaymentProcessingException('Operacja oczekuje na potwierzenie wykonania');
        }
        if (!($response['purchase_units'][0]['amount']['value'] ?? false)) {
            throw new ContentException(['paymentId' => 'Podana operacja nie istnieje lub wystąpił problem po stronie serwera.']);
        }
    }
}
