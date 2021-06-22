<?php

namespace MNGame\Service\Payment;

use Exception;
use MNGame\Database\Entity\Payment;
use MNGame\Service\Payment\Client\PaymentClientInterface;

class PaymentService extends AbstractPayment
{
    /**
     * @throws Exception
     */
    public function executePayment(array $data, string $username, Payment $payment): float
    {
        /** @var PaymentClientInterface $client */
        $client = $this->clientFactory->create($payment);
        $amount = $client->executeRequest($data);

        $this->notePayment($amount, $username, $payment->getType()->getKey(), $data['paymentId'], $data['status']);

        return (float)$amount;
    }
}
