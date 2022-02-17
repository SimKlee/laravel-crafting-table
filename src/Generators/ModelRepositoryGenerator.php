<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use File;

class ModelRepositoryGenerator extends AbstractGenerator
{
    protected string $template  = 'model_repository';
    protected bool   $class     = true;
    protected string $namespace = 'App\Models\Repositories';

    protected function beforeWrite(): void
    {
        // @TODO: make dynamic
        $this->setExtends(\SimKlee\LaravelCraftingTable\Models\Repositories\AbstractModelRepository::class);
    }
}
