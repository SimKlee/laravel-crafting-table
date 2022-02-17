<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Support\Collection;
use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;

class ModelDefinitionBag
{
    private Collection $bag;

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoDataTypeKeywordFoundException
     * @throws NoCastTypeForDataTypeException
     */
    public function __construct(array $config = null)
    {
        $this->bag = new Collection();

        if (!is_null($config)) {
            foreach ($config as $model => $definitions) {
                $modelDef = new ModelDefinition(model: $model, definition: $definitions);
                $this->bag->put(key: $model, value: $modelDef);
            }

            $this->processForeignKeys();
        }
    }

    public function add(ModelDefinition $modelDefinition): void
    {
        $this->bag->put(key: $modelDefinition->model, value: $modelDefinition);
    }

    public function has(string $model): bool
    {
        return $this->bag->has(key: $model);
    }

    public function get(string $model): ModelDefinition|null
    {
        return $this->bag->get(key: $model, default: null);
    }

    public function getModelDefinitions(): Collection
    {
        return $this->bag->values();
    }

    public function processForeignKeys(): void
    {
        $this->bag->each(function (ModelDefinition $modelDefinition, string $model) {
            $modelDefinition->getColumnsWithForeignKey()
                            ->each(function (ColumnDefinition $columnDefinition) {
                                $this->syncColumnDefinitionsFromForeignKey($columnDefinition);
                            });
        });
    }

    private function syncColumnDefinitionsFromForeignKey(ColumnDefinition $columnDefinition): void
    {
        $foreignKeyColumnDefinition = $this->get(model: $columnDefinition->foreignKeyModel)
                                           ->getColumn(name: $columnDefinition->foreignKeyColumn);

        $columnDefinition->dataType     = $foreignKeyColumnDefinition->dataType;
        $columnDefinition->dataTypeCast = $foreignKeyColumnDefinition->dataTypeCast;
        $columnDefinition->unsigned     = $foreignKeyColumnDefinition->unsigned;
        $columnDefinition->length       = $foreignKeyColumnDefinition->length;
        $columnDefinition->decimals     = $foreignKeyColumnDefinition->decimals;
        $columnDefinition->precision    = $foreignKeyColumnDefinition->precision;
    }
}
