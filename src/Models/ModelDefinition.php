<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use Illuminate\Support\Collection;

class ModelDefinition
{
    public ?string    $model;
    public ?string    $table;
    public Collection $columns;
    public bool       $timestamps = false;
    public bool       $softDelete = false;
    public bool       $uuid       = false;

    public function __construct(array $definition = [])
    {
        $this->columns = new Collection();

        if (count($definition) > 0) {
            $this->fromArray($definition);
        }
    }

    private function fromArray(array $definition)
    {
        $this->table = $definition['table'];

    }
}
