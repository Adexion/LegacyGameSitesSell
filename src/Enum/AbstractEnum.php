<?php

namespace ModernGame\Enum;

use ReflectionClass;
use ReflectionException;

abstract class AbstractEnum
{
    /**
     * @throws ReflectionException
     */
    public static function toArray(): array
    {
        $class = new ReflectionClass(get_called_class());

        return $class->getConstants();
    }
}