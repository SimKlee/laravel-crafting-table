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
                $this->bag->add(new ModelDefinition($model, $definitions));
            }
        }
    }

    public function add(ModelDefinition $modelDefinition): void
    {
        $this->bag->put($modelDefinition->model, $modelDefinition);
    }

    public function has(string $model): bool
    {
        return $this->bag->has($model);
    }

    public function get(string $model): ModelDefinition|null
    {
        return $this->bag->get($model, null);
    }

    public function getModelDefinitions(): Collection
    {
        return $this->bag->values();
    }

}
