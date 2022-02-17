<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

class ModelMigrationGenerator extends AbstractGenerator
{
    protected string $template  = 'model_migration';
    protected bool   $class     = true;
    protected string $namespace = '';

    protected function beforeWrite(): void
    {
        $this->setExtends(\SimKlee\LaravelCraftingTable\Models\Migration\Migration::class);
        $this->addUses([
            \Illuminate\Database\Schema\Blueprint::class,
            \Illuminate\Support\Facades\Schema::class,
            sprintf('App\Models\%s', $this->modelDefinition->model),
        ]);

        // @TODO: find other/older migration of same model; confirm: (o)verwrite, (s)kip, (r)eplace, (a)bort
    }

}
