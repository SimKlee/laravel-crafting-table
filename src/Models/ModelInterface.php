<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Support\Collection;
use SimKlee\LaravelCraftingTable\Models\Queries\ModelQueryInterface;
use SimKlee\LaravelCraftingTable\Models\Repositories\ModelRepositoryInterface;

interface ModelInterface
{
    public function newEloquentBuilder($query): ModelQueryInterface;

    public static function repository(): ModelRepositoryInterface;

    public static function column(string $column, string|null $alias = null): string;

    public function getModelName(): string;

    public static function createFake(array $attributes = []): ModelInterface;

    public static function makeFake(array $attributes = []): ModelInterface;

    public static function createFakes(int $count, array $attributes = []): Collection;

    public static function makeFakes(int $count, array $attributes = []): Collection;

    public static function getModelAttributes(): Collection;

    public static function hasAttribute($attribute): bool;
}
