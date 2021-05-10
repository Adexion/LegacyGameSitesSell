<?php

namespace MNGame\Enum;

use ReflectionClass;
use ReflectionException;

abstract class AbstractEnum
{
    private $value;

    /**
     * @throws ReflectionException
     */
    public static function toArray(): array
    {
        $reflectionClass = new ReflectionClass(get_called_class());

        return $reflectionClass->getConstants();
    }

    /**
     * @throws ReflectionException
     */
    public static function getKeys(): array
    {
        $reflectionClass = new ReflectionClass(get_called_class());

        return array_keys($reflectionClass->getConstants());
    }

    /**
     * @throws ReflectionException
     */
    public static function getValues(): array
    {
        return array_values(self::toArray());
    }

    public static function getValueByKey(string $key)
    {
        return constant(sprintf('%s::%s', get_called_class(), $key));
    }

    /**
     * @throws ReflectionException
     */
    public static function create($value): AbstractEnum
    {
        return new static($value);
    }

    /**
     * @throws UnexpectedValueException|ReflectionException
     */
    public function __construct($value)
    {
        $constList = self::toArray();

        if (!in_array($value, $constList, true)) {
            $key = array_search($value, $constList);
            if ($key === false) {
                throw new UnexpectedValueException(sprintf(
                    'Enum class "%s" cannot be created from value "%s"',
                    get_class($this), var_export($value, true)
                ));
            }

            $this->value = $constList[$key];
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @throws ReflectionException
     */
    public function getKey(): ?string
    {
        foreach (self::toArray() as $key => $value) {
            if ($value == $this->value) {
                return (string)$key;
            }
        }

        return null;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function isEqual(AbstractEnum $enum): bool
    {
        return $this->value == (string)$enum;
    }
}