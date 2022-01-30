<?php /** @var \SimKlee\LaravelCraftingTable\Models\ModelDefinition $modelDefinition */ ?>
<?php /** @var \SimKlee\LaravelCraftingTable\Generators\AbstractGenerator $generator */ ?>
declare(strict_types=1);

namespace {{ $generator->getNamespace() }};

@foreach($generator->getUses() as $class)
use {{ $class }};
@endforeach

class {{ $modelDefinition->model }}Query extends {{ $generator->getExtends() }}
{

}
