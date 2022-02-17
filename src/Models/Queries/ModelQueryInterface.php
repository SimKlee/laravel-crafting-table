<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Queries;

interface ModelQueryInterface
{
    public function createJoin(string $type, string $table, string $condition1, string $condition2, string $with = null, string $groupBy = null): ModelQueryInterface;

}
