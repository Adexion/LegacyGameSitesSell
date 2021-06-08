<?php

namespace MNGame\Service\Connection\Payment;

use RuntimeException;
use MNGame\Util\EnumKeyToCamelCaseConverter;

class PaymentTypeFormFactory
{
    public function create(string $paymentType): string
    {
        $camelCase = EnumKeyToCamelCaseConverter::getCamelCase($paymentType);
        $className = 'MNGame\\Form\\' . $camelCase . 'Type';

        if (!class_exists($className)) {
            throw new RuntimeException('FormType ' . $className . ' does not exist');
        }

        return $className;
    }
}