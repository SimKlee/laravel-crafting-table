<?php /** @var \SimKlee\LaravelCraftingTable\Models\Definitions\ModelDefinition $modelDefinition */ ?>
<?php /** @var \SimKlee\LaravelCraftingTable\Generators\AbstractGenerator $generator */ ?>
declare(strict_types=1);

namespace {{ $generator->getNamespace() }};

@foreach($generator->getUses() as $class)
use {{ $class }};
@endforeach

class {{ $modelDefinition->model }}Test extends ModelTestCase
{
    protected function getModelClass(): string
    {
        return {{ $modelDefinition->model }}::class;
    }

    protected function getModelColumns(): Collection
    {
        return collect([
<?php /** @var \SimKlee\LaravelCraftingTable\Models\Definitions\ColumnDefinition $columnDefinition */ ?>
@foreach($modelDefinition->columns as $columnDefinition)
            {{ $modelDefinition->model }}::PROPERTY_{{ Str::upper($columnDefinition->name) }},
@endforeach
        ]);
    }
}
