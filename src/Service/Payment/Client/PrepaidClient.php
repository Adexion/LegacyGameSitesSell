<?php

namespace MNGame\Service\Payment\Client;

use MNGame\Exception\ContentException;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Service\ApiClient\RestApiClient;
use MNGame\Exception\PaymentProcessingException;

class PrepaidPaymentClient extends DefaultPaymentClient implements PaymentClientInterface
{
    /**
     * @param array $data
     * @return string|null
     */
    public function executeRequest(array $data): ?string
    {
        var_dump($data);die;
    }
}
