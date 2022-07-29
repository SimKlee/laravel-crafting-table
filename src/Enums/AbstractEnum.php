<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Enums;

use ReflectionClass;
use ReflectionClassConstant;
use ValueError;

abstract class AbstractEnum
{
    public string|int $value;

    public function __construct($value)
    {
        if (!in_array($value, self::cases())) {
            throw new ValueError($value);
        }

        $this->value = $value;
    }

    public static function cases(): array
    {
        collect((new ReflectionClass(self::class))->getConstants())
            ->filter(function (ReflectionClassConstant $reflectionClassConstant) {
                return $reflectionClassConstant->isPublic();
            })
            ->mapWithKeys(function (ReflectionClassConstant $reflectionClassConstant) {
                return $reflectionClassConstant->getValue();
            })->toArray();
    }

    /**
     * @throws ValueError
     */
    public static function from(string|int $value): AbstractEnum
    {
        return new static($value);
    }

    public static function tryFrom(string|int $value): AbstractEnum|null
    {
        try {
            self::from($value);
        } catch (ValueError $e) {
            return null;
        }
    }
}