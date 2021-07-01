<?php

namespace MNGame\Enum;

use ReflectionException;
use MNGame\Util\UnderscoreToCamelCaseConverter;

class PaymentTypeEnum extends AbstractEnum
{
    public const HOTPAY_SMS = 1;
    public const DIRECT_BILL = 2;
    public const HOTPAY = 3;
    public const PAY_SAFE_CARD = 4;
    public const PREPAID = 5;
    public const VOUCHER = 6;
    public const MICRO_SMS = 7;
}
