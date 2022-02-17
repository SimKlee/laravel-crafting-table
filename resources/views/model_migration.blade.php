<?php /** @var \SimKlee\LaravelCraftingTable\Models\Definitions\ModelDefinition $modelDefinition */ ?>
<?php /** @var \SimKlee\LaravelCraftingTable\Generators\AbstractGenerator $generator */ ?>

@foreach($generator->getUses() as $class)
use {{ $class }};
@endforeach

class Create{{ $modelDefinition->model }}Table extends {{ $generator->getExtends() }}
{
    public function up(): void
    {
        Schema::create({{ $modelDefinition->model }}::TABLE, function (Blueprint $table) {
@foreach($modelDefinition->columns as $columnDefinition)
            {!! \SimKlee\LaravelCraftingTable\Generators\Formatters\ColumnMigrationFormatter::create($modelDefinition->model, $columnDefinition)->toString() !!}
@endforeach
        });
    }

    public function down(): void
    {
        Schema::dropIfExists({{ $modelDefinition->model }}::TABLE);
    }
}
