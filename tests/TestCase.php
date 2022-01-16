<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SimKlee\LaravelCraftingTable\LaravelCraftingTableServiceProvider;

/**
 * Class TestCase
 * @package SimKlee\LaravelBakery\Tests
 */
class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelCraftingTableServiceProvider::class,
        ];
    }
}
