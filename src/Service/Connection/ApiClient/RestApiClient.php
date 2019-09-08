<?php

namespace ModernGame\Service\Connection\ApiClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ModernGame\Exception\ContentException;
use ModernGame\Service\EnvironmentService;

class RestApiClient
{
    const GET = 'GET';
    const POST = 'POST';

    private $client;
    private $env;

    public function __construct(EnvironmentService $env)
    {
        $this->client = new Client(['timeout' => 10]);
        $this->env = $env;
    }

    /**
     * @throws GuzzleException
     * @throws ContentException
     */
    public function request(string $method, string $url, $data = null): string
    {
        if ($this->env->isTest()) {
            return MockProvider::getMock($url, json_encode($data));
        }

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