<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Tests\Unit;

use PHPUnit\Framework\TestCase;

class UnitTestCase extends TestCase
{
    public function getResourcePath(string|null $path = null): string
    {
        $resourcePath = __DIR__ . '/../resources/';
        if (!is_null($path)) {
            $resourcePath .= $path;
        }

        return $resourcePath;
    }
}
