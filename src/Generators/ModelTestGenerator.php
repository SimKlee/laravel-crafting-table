<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

class ModelTestGenerator extends AbstractGenerator
{
    protected string $template  = 'model_test';
    protected bool   $class     = true;
    protected string $namespace = 'Tests\Feature\Models';

    protected function beforeWrite(): void
    {
        $this->addUses([
            \Illuminate\Support\Collection::class,
            \SimKlee\LaravelCraftingTable\Tests\ModelTestCase::class,
            sprintf('App\Models\%s', $this->modelDefinition->model),
        ]);
    }

}
