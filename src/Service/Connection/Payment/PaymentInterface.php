<?php

namespace ModernGame\Service\Connection\Payment;

interface PaymentInterface
{
    public function executePayment(string $id): float;
}
