<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Support\Collection;

abstract class AbstractFakeModel
{
    abstract protected function getAttributes(): array;

    abstract protected function getForeignKeyColumns(): array;

    public function create(array $attributes = [], int $count = 1): AbstractModel
    {

    }

    public function make(array $attributes = [], int $count = 1): Collection
    {

    }
}
