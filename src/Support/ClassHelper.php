<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Support;

class ClassHelper
{
    public static function getNamespace(string $class): string
    {
        $parts = explode('\\', $class);
        array_pop($parts);

        return implode('\\', $parts);
    }

    public static function inNamespace(string $class, string $namespace): bool
    {
        return self::getNamespace($class) === $namespace;
    }
}
