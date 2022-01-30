<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use SimKlee\LaravelCraftingTable\Models\Queries\AbstractModelQuery;
use SimKlee\LaravelCraftingTable\Models\Repositories\AbstractModelRepository;

abstract class AbstractModel extends Model
{
    public const TABLE = '';

    public const DELETED_AT = 'deleted_at';

    /**
     * @param Builder $query
     *
     * @return AbstractModelQuery
     */
    public function newEloquentBuilder($query): AbstractModelQuery
    {
        $class = sprintf('\App\Models\Queries\%sQuery', class_basename(static::class));

        return new $class($query);
    }

    public static function repository(): AbstractModelRepository
    {
        $class = sprintf('\App\Models\Repositories\%sRepository', class_basename(static::class));

        return new $class();
    }

    public static function column(string $column, string|null $alias = null): string
    {
        if (is_null($alias)) {
            return sprintf('%s.%s', static::TABLE, $column);
        }

        return sprintf('%s.%s AS %s', static::TABLE, $column, $alias);
    }

    public function getModelName(): string
    {
        return class_basename($this);
    }

    public static function createFake(array $attributes = []): AbstractModel
    {
        return static::factory()->create($attributes);
    }

    public static function makeFake(array $attributes = []): AbstractModel
    {
        return static::factory()->make($attributes);
    }

    public static function createFakes(int $count, array $attributes = []): Collection
    {
        return static::factory()->count($count)->create($attributes);
    }

    public static function makeFakes(int $count, array $attributes = []): Collection
    {
        return static::factory()->count($count)->make($attributes);
    }

    public static function getModelAttributes(): Collection
    {
        return collect(Schema::getColumnListing(static::TABLE));
    }

    public static function hasAttribute($attribute): bool
    {
        return Schema::hasColumn(static::TABLE, $attribute);
    }
}
