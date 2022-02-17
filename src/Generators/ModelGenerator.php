<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimKlee\LaravelCraftingTable\Models\Definitions\ColumnDefinition;

class ModelGenerator extends AbstractGenerator
{
    protected string $template = 'model';
    protected bool   $class    = true;
    protected string $namespace = 'App\Models';

    protected function beforeWrite(): void
    {
        // @TODO: make dynamic
        $this->setExtends(\SimKlee\LaravelCraftingTable\Models\AbstractModel::class);
        $this->addUse(\Illuminate\Support\Collection::class);
        $this->addUse(sprintf('App\Models\Queries\%sQuery', $this->modelDefinition->model));
        $this->addUses($this->uses);

        if ($this->modelDefinition->hasDates()) {
            $this->addUse(Carbon::class);
        }

        if ($this->modelDefinition->softDelete) {
            $this->addUse(SoftDeletes::class);
        }

        $columnsWithForeignKeys = $this->modelDefinition->getColumnsWithForeignKey();

        if ($columnsWithForeignKeys->count() > 0) {
            $this->addUse(BelongsTo::class);
        }

        $columnsWithForeignKeys->each(function (ColumnDefinition $columnDefinition) {
            #$this->addUse(sprintf('App\Models\%s', $columnDefinition->foreignKeyModel));
        });
    }

}
