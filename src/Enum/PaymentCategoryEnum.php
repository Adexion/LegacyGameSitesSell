<?php

namespace MNGame\Enum;

use ReflectionException;
use MNGame\Util\UnderscoreToCamelCaseConverter;

class PaymentCategoryEnum extends AbstractEnum
{
    public const SMS = 'SMS';
    public const CARD = 'CARD';
    public const OTHER = 'OTHER';
}
