<?php

namespace MNGame\Service\Payment;

use ReflectionException;
use MNGame\Enum\PaymentTypeEnum;
use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;

class PaymentConfigurationFormBuilder
{
    private string $uniqId;

    public function __construct(string $uniqId)
    {
        $this->uniqId = $uniqId;
    }

    /**
     * @throws ReflectionException
     */
    public function build(Payment $payment, float $price, string $name, string $uri): array
    {
        /** @var Configuration $configuration */
        foreach ($payment->getConfigurations() as $configuration) {
            switch ($configuration->getType()) {
                case PaymentConfigurationType::GENERATE_ID:
                    $arr[$configuration->getName()] = $this->uniqId;
                    break;
                case PaymentConfigurationType::STRING:
                case PaymentConfigurationType::INPUT:
                    $arr[$configuration->getName()] = $configuration->getValue();
                    break;
                case PaymentConfigurationType::URI:
                    $arr[$configuration->getName()] = $uri . ($name !== PaymentTypeEnum::create(PaymentTypeEnum::PREPAID)->getKey() ? $configuration->getValue() : 'prepaid/status');
                    break;
                case PaymentConfigurationType::PRICE:
                    $arr[$configuration->getName()] = $price;
                    break;
                case PaymentConfigurationType::NAME:
                    $arr[$configuration->getName()] = $name;
                    break;
            }
        }

        return $arr ?? [];
    }

    public function getMethod(Payment $payment)
    {
        return current(array_filter($payment->getConfigurations()->toArray(), function (Configuration $conf) {
            return $conf->getType() === PaymentConfigurationType::METHOD;
        }))->getValue();
    }
}