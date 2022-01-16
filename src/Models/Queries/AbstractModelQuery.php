<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractModelQuery extends Builder
{
    protected const JOIN_TYPE_INNER = 'inner';
    protected const JOIN_TYPE_LEFT = 'left';

    /**
     * @throws UnknownJoinTypeException
     */
    protected function createJoin(
        string $type,
        string $table,
        string $condition1,
        string $condition2,
        string|null $with = null,
        string|null $groupBy = null
    ): AbstractModelQuery {
        $method = match ($type) {
            self::JOIN_TYPE_INNER => 'join',
            self::JOIN_TYPE_LEFT => 'leftJoin',
            default => throw new UnknownJoinTypeException($type)
        };

        return $this->{$method}($table, $condition1, $condition2)
            ->when(!is_null($with), function (AbstractModelQuery $query) use ($with) {
                return $query->with($with);
            })
            ->when(!is_null($groupBy), function (AbstractModelQuery $query) use ($groupBy) {
                return $query->groupBy($groupBy);
            });
    }
}
