<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;
use SimKlee\LaravelCraftingTable\Generators\ModelGenerator;
use SimKlee\LaravelCraftingTable\Generators\ModelQueryGenerator;
use SimKlee\LaravelCraftingTable\Models\ModelDefinition;
use SimKlee\LaravelCraftingTable\Models\ModelDefinitionBag;

class ModelCrafterCommand extends AbstractCrafterCommand
{
    /** @var string */
    protected $signature = 'craft:model {name}';

    /** @var string */
    protected $description = '';

    private ModelDefinitionBag $bag;

    public function __construct()
    {
        parent::__construct();

        $this->bag = new ModelDefinitionBag(config('models'));
    }

    public function handle(): int
    {
        $model           = $this->argument('name');
        $modelDefinition = $this->bag->get($model);

        $this->writeModel($modelDefinition);
        $this->writeModelQuery($modelDefinition);

        return self::SUCCESS;
    }

    private function writeModel(ModelDefinition $modelDefinition)
    {
        $generator = new ModelGenerator($modelDefinition);
        $generator->setExtends(\SimKlee\LaravelCraftingTable\Models\AbstractModel::class);
        $generator->addUse(\Illuminate\Support\Collection::class);
        $generator->addUses($modelDefinition->uses);

        $generator->write(sprintf('app/Models/%s.php', $modelDefinition->model), true);
        $bin = './vendor/simklee/laravel-crafting-table/' . 'vendor/bin/phpcbf';
        exec(sprintf('%s app/Models/%s.php', $bin, $modelDefinition->model));
    }

    private function writeModelQuery(ModelDefinition $modelDefinition)
    {
        $generator = new ModelQueryGenerator($modelDefinition);
        $generator->setExtends(\SimKlee\LaravelCraftingTable\Models\Queries\AbstractModelQuery::class);

        $generator->write(sprintf('app/Models/Queries/%sQuery.php', $modelDefinition->model), true);
        $bin = './vendor/simklee/laravel-crafting-table/' . 'vendor/bin/phpcbf';
        exec(sprintf('%s app/Models/%s.php', $bin, $modelDefinition->model));
    }
}
