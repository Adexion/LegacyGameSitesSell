<?php

namespace ModernGame\Service\Connection\Payment\DotPay;

use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\Connection\ApiClient\RestApiClient;

class DotPayClient extends RestApiClient
{
    const API_OPERATION = 'operations/%s/';
    const API_URI = 'https://ssl.dotpay.pl/test_seller/api/v1/';

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function executeRequest(string $username, string $password, string $operationName): array
    {
        $request['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
        ];

        return json_decode(
            $this->request(
                self::GET,
                self::API_URI . sprintf(self::API_OPERATION, $operationName),
                $request), true
            ) ?? [];
    }
}
