<?php

namespace ModernGame\Service\Connection\ApiClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RestApiClient
{
    const GET = 'GET';
    const POST = 'POST';

    private $client;

    public function __construct(array $config = [])
    {
        $this->client = new Client(array_merge($config, ['timeout' => 10]));
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, string $url, $data = null): string
    {
        try {
            return (string)$this->client
                ->request($method, $url, empty($data) ? [] : $data)
                ->getBody()
                ->getContents();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
