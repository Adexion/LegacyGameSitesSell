<?php

namespace MNGame\Service\Connection\Payment;

use MNGame\Database\Entity\Payment;
use MNGame\Database\Repository\SMSPriceRepository;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Service\EnvironmentService;
use MNGame\Util\EnumKeyToCamelCaseConverter;
use ReflectionException;

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
        $camelCase = EnumKeyToCamelCaseConverter::getCamelCase($payment->getType()->getKey());
        $className = 'MNGame\\Service\\Connection\\Payment\\Client\\'.$camelCase.'Client';

        if (!class_exists($className)) {
            throw new RuntimeException('Class '.$className.' does not exist');
        }

        if ($payment->getType()->getValue() === PaymentTypeEnum::MICRO_SMS || $payment->getType()->getValue() === PaymentTypeEnum::HOT_PAY_SMS) {
            return new $className($this->smsPriceRepository, $payment->getConfigurations(), $this->environmentService, $camelCase);
        }

        return new $className($this->smsPriceRepository, $payment->getConfigurations(), $this->environmentService, $camelCase);
    }
}