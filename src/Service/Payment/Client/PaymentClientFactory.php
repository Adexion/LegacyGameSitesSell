<?php

namespace MNGame\Service\Payment\Client;

use ReflectionException;
use MNGame\Database\Entity\Payment;
use MNGame\Service\User\WalletService;
use MNGame\Util\UnderscoreToCamelCaseConverter;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Database\Repository\PaymentHistoryRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentClientFactory
{
    private SMSPriceRepository $smsPriceRepository;
    private PaymentHistoryRepository $paymentHistoryRepository;
    private TokenStorageInterface $tokenStorage;

    public function __construct(SMSPriceRepository $smsPriceRepository, PaymentHistoryRepository $paymentHistoryRepository, TokenStorageInterface $tokenStorage)
    {
        $this->smsPriceRepository       = $smsPriceRepository;
        $this->paymentHistoryRepository = $paymentHistoryRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     *
     * @throws ReflectionException
     */
    public function create(Payment $payment): PaymentClientInterface
    {
        $camelCase = UnderscoreToCamelCaseConverter::getCamelCase($payment->getType()->getKey());
        $className = 'MNGame\\Service\\Payment\\Client\\' . $camelCase . 'Client';

        if (!class_exists($className)) {
            $className = 'MNGame\\Service\\Payment\\Client\\DefaultPaymentClient';
        }

        return new $className($this->smsPriceRepository, $payment->getConfigurations(), $this->paymentHistoryRepository, $this->tokenStorage);
    }
}