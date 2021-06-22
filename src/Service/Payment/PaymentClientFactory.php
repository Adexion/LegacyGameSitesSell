<?php

namespace MNGame\Service\Payment;

use RuntimeException;
use ReflectionException;
use MNGame\Database\Entity\Payment;
use MNGame\Service\EnvironmentService;
use MNGame\Util\UnderscoreToCamelCaseConverter;
use MNGame\Database\Repository\SMSPriceRepository;

class PaymentClientFactory
{
    private EnvironmentService $environmentService;
    private SMSPriceRepository $smsPriceRepository;

    public function __construct(SMSPriceRepository $smsPriceRepository, EnvironmentService $environmentService)
    {
        $this->smsPriceRepository = $smsPriceRepository;
        $this->environmentService = $environmentService;
    }

    /**
     * @throws ReflectionException
     */
    public function create(Payment $payment)
    {
        $camelCase = UnderscoreToCamelCaseConverter::getCamelCase($payment->getType()->getKey());
        $className = 'MNGame\\Service\\Connection\\Payment\\Client\\' . $camelCase . 'Client';

        if (!class_exists($className)) {
            throw new RuntimeException('Class ' . $className . ' does not exist');
        }

        return new $className($this->smsPriceRepository, $payment->getConfigurations(), $this->environmentService, $camelCase);
    }
}