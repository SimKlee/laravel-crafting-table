<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Collection;

abstract class ModelTestCase extends TestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $app = require app_path('/../bootstrap/app.php');
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    abstract protected function getModelClass(): string;

    abstract protected function getModelColumns(): Collection;

    public function dataProviderForColumnTests(): array
    {
        return $this->getModelColumns()->mapWithKeys(function (string $column) {
            return [$column => ['column' => $column]];
        })->toArray();
    }

    /**
     * @dataProvider dataProviderForColumnTests
     */
    public function testIfModelColumnsExists(string $column): void
    {
        $class = $this->getModelClass();
        $table = $class::TABLE;
        $this->assertTrue(\Schema::hasColumn($class::TABLE, $column), 'Missing column: ' . $column);
    }
}
