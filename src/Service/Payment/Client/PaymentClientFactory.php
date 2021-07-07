<?php

namespace MNGame\Service\Payment\Client;

use ReflectionException;
use MNGame\Database\Entity\Payment;
use MNGame\Service\User\WalletService;
use MNGame\Util\UnderscoreToCamelCaseConverter;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;

class PaymentClientFactory
{
    private SMSPriceRepository $smsPriceRepository;
    private PaymentHistoryRepository $paymentHistoryRepository;
    private WalletService $walletService;

    public function __construct(SMSPriceRepository $smsPriceRepository, PaymentHistoryRepository $paymentHistoryRepository, WalletService $walletService)
    {
        $this->smsPriceRepository       = $smsPriceRepository;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->walletService = $walletService;
    }

    /**
     * @throws ReflectionException
     */
    public function create(Payment $payment): PaymentClientInterface
    {
        $camelCase = UnderscoreToCamelCaseConverter::getCamelCase($payment->getType()->getKey());
        $className = 'MNGame\\Service\\Connection\\Payment\\Client\\' . $camelCase . 'Client';

        if (!class_exists($className)) {
            $className = 'MNGame\\Service\\Connection\\Payment\\Client\\DefaultPaymentClient';
        }

        return new $className($this->smsPriceRepository, $payment->getConfigurations(), $this->paymentHistoryRepository, $this->walletService);
    }
}