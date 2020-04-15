<?php

namespace ModernGame\Service\Connection\Payment;

interface PaymentInterface
{
    public function executePayment($id): float;
}
