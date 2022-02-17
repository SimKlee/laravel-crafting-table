<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Support\Collection;
use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\UnknownForeignKeyColumnNameSyntaxException;

class ModelDefinition
{
    private array                $definition;
    public ?string               $model;
    public ?string               $table;
    public Collection            $columns;
    public array                 $values;
    public array                 $defaults;
    public bool                  $timestamps           = false;
    public bool                  $softDelete           = false;
    public bool                  $uuid                 = false;
    public Collection            $traits;
    public Collection            $uses;
    public ?ForeignKeyDefinition $foreignKeyDefinition = null;

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    public function __construct(string $model, array $definition = [])
    {
        $this->model      = $model;
        $this->definition = $definition;
        $this->columns    = new Collection();
        $this->traits     = new Collection();
        $this->uses       = new Collection();

        $this->processDefinitions();
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    private function fromArray(): void
    {
        $this->table      = $this->fromDefinitionArrayIfExists('table');
        $this->values     = $this->fromDefinitionArrayIfExists('values', []);
        $this->defaults   = $this->fromDefinitionArrayIfExists('defaults', []);
        $this->timestamps = $this->fromDefinitionArrayIfExists('timestamps', false);
        $this->softDelete = $this->fromDefinitionArrayIfExists('softDelete', false);
        $this->uuid       = $this->fromDefinitionArrayIfExists('uuid', false);


        foreach ($this->fromDefinitionArrayIfExists('columns') as $column => $definition) {
            $columnDefinition = (new ColumnParser($column, $definition))->getColumnDefinition();
            $this->columns->put($column, $columnDefinition);
            if ($columnDefinition->dataTypeCast === 'Carbon') {
                $this->addUses(\Carbon\Carbon::class);
            }
        }

    }

    private function isForeignKeyDefinition(string $definition): bool
    {
        if (str_contains($definition, 'fk') || str_contains($definition, 'foreignKey')) {
            return true;
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function getColumnsWithForeignKey(): Collection
    {
        return $this->columns->filter(function (ColumnDefinition $columnDefinition) {
            return $columnDefinition->foreignKey;
        });
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoDataTypeKeywordFoundException
     * @throws NoCastTypeForDataTypeException
     */
    private function processDefinitions()
    {
        $this->fromArray();

        $this->processTimestamps();
        $this->processSoftDelete();
    }

    private function fromDefinitionArrayIfExists(string $key, mixed $default = null): mixed
    {
        if (isset($this->definition[ $key ])) {
            return $this->definition[ $key ];
        }

        return $default;
    }

    public function hasDates(): bool
    {
        return collect($this->columns)->filter(function (ColumnDefinition $columnDefinition) {
                return $columnDefinition->dataTypeCast === 'Carbon';
            })->count() > 0;
    }

    public function getDates(): array
    {
        return collect($this->columns)->filter(function (ColumnDefinition $columnDefinition) {
            return $columnDefinition->dataTypeCast === 'Carbon';
        })->map(function (ColumnDefinition $columnDefinition) {
            return $columnDefinition->name;
        })->toArray();
    }

    /**
     * @throws NoDataTypeKeywordFoundException
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     */
    private function processTimestamps()
    {
        if ($this->timestamps) {
            $this->addColumn(AbstractModel::CREATED_AT, 'timestamp');
            $this->addColumn(AbstractModel::UPDATED_AT, 'timestamp|nullable');
        }
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    private function processSoftDelete()
    {
        if ($this->softDelete) {
            $this->addColumn(AbstractModel::DELETED_AT, 'timestamp|nullable');
            $this->addTrait(\Illuminate\Database\Eloquent\SoftDeletes::class);
        }
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    private function addColumn(string $name, string $definition)
    {
        if ($this->columns->has($name) === false) {
            $this->columns->put($name, (new ColumnParser($name, $definition))->getColumnDefinition());
        }
    }

    public function getColumn(string $name): ColumnDefinition
    {
        return $this->columns->get($name);
    }

    private function addTrait(string $class)
    {
        if ($this->traits->contains($class) === false) {
            $this->traits->add(class_basename($class));
            $this->addUses($class);
        }
    }

    private function addUses(string $class)
    {
        if ($this->uses->contains($class) === false) {
            $this->uses->add($class);
        }
    }


}
