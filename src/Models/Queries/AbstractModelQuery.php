<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractModelQuery extends Builder implements ModelQueryInterface
{
    protected const JOIN_TYPE_INNER = 'inner';
    protected const JOIN_TYPE_LEFT  = 'left';

    /**
     * @throws UnknownJoinTypeException
     */
    public function createJoin(
        string $type,
        string $table,
        string $condition1,
        string $condition2,
        string $with = null,
        string $groupBy = null
    ): ModelQueryInterface
    {
        $method = match ($type) {
            self::JOIN_TYPE_INNER => 'join',
            self::JOIN_TYPE_LEFT  => 'leftJoin',
            default               => throw new UnknownJoinTypeException($type)
        };

        return $this->{$method}($table, $condition1, $condition2)
                    ->when(is_string($with), function (AbstractModelQuery $query) use ($with) {
                        return $query->with($with);
                    })
                    ->when(!is_null($groupBy), function (AbstractModelQuery $query) use ($groupBy) {
                        return $query->groupBy($groupBy);
                    });
    }
}
