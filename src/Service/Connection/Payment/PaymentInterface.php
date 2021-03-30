<?php

namespace MNGame\Service\Connection\Payment;

interface PaymentInterface
{
    public function executePayment(string $id, string $username): float;
}
