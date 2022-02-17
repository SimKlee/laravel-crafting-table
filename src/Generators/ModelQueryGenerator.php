<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use File;

class ModelQueryGenerator extends AbstractGenerator
{
    protected string $template  = 'model_query';
    protected bool   $class     = true;
    protected string $namespace = 'App\Models\Queries';

    protected function beforeWrite(): void
    {
        // @TODO: make dynamic
        $this->setExtends(\SimKlee\LaravelCraftingTable\Models\Queries\AbstractModelQuery::class);
    }

}
