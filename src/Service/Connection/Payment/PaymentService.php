<?php

namespace MNGame\Service\Connection\Payment;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MNGame\Database\Entity\Payment;

class PaymentService extends AbstractPayment
{
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function executePayment(array $data, string $username, Payment $payment): float
    {
        $client = $this->clientFactory->create($payment);
        $amount = $client->executeRequest($data);

        $this->notePayment($amount, $username, $payment->getType()->getKey(), $data['paymentId']);

        return (float)$amount;
    }
}
