<?php

namespace ModernGame\Service\Connection\Payment\DotPay;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Exception\PaymentProcessingException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;

class DotPayClient extends RestApiClient
{
    private const PAYMENT_COMPLETED = 'completed';
    private const PAYMENT_REJECTED = 'rejected';

    private const API_OPERATION = 'operations/%s/';
    private const API_URI = 'https://ssl.dotpay.pl/test_seller/api/v1/';

    /**
     * @throws GuzzleException
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    public function executeRequest(string $username, string $password, string $operationName): array
    {
        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
        ];

        $rawResponse = $this->request(
            self::GET,
            self::API_URI . sprintf(self::API_OPERATION, $operationName),
            $request
        );

        $response = json_decode($rawResponse, true) ?? [];
        $this->handleException($response);

        return $response;
    }

    /**
     * @throws ContentException
     * @throws PaymentProcessingException
     */
    private function handleException(array $response)
    {
        if (empty($response)) {
            throw new ContentException(['error' => 'Nie można nawiązać połączenia z serwerem płatności.']);
        }
        if (isset($response['details'])) {
            throw new ContentException(['error' => 'Podana płatność nie istnieje']);
        }
        if ($response['status'] === self::PAYMENT_REJECTED) {
            throw new ContentException(['error' => 'Twoja płatność została odrzucona']);
        }
        if ($response['status'] !== self::PAYMENT_COMPLETED) {
            throw new PaymentProcessingException('Płatność oczekuje na potwierzenie wykonania');
        }
    }
}
