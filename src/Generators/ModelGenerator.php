<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use File;
use SimKlee\LaravelCraftingTable\Models\ModelDefinition;

class ModelGenerator extends AbstractGenerator
{
    protected string $template = 'model';
    protected bool   $class    = true;
    protected string $namespace = 'App\Models';

}
