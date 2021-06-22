<?php

namespace MNGame\Service\Payment;

use MNGame\Database\Entity\Payment;
use MNGame\Database\Entity\ItemList;
use MNGame\Database\Entity\Configuration;
use MNGame\Enum\PaymentConfigurationType;

class PaymentConfigurationFormBuilder
{
    private string $uniqId;

    public function __construct(string $uniqId)
    {
        $this->uniqId = $uniqId;
    }

    public function build(Payment $payment, ItemList $itemList, string $uri): array
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
                    $arr[$configuration->getName()] = $uri . $configuration->getValue();
                    break;
                case PaymentConfigurationType::PRICE:
                    $arr[$configuration->getName()] = (float)$itemList->getPrice();
                    break;
                case PaymentConfigurationType::NAME:
                    $arr[$configuration->getName()] = (float)$itemList->getName();
                    break;
            }
        }

        return $arr ?? [];
    }

    public function getMethod(Payment $payment)
    {
        return array_filter($payment->getConfigurations()->toArray(), function (Configuration $conf) {
            return $conf->getType() === PaymentConfigurationType::METHOD;
        })[0]->getValue();
    }
}