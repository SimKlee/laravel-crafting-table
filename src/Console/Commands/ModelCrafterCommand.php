<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use Carbon\Carbon;
use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;
use SimKlee\LaravelCraftingTable\Generators\ModelGenerator;
use SimKlee\LaravelCraftingTable\Generators\ModelMigrationGenerator;
use SimKlee\LaravelCraftingTable\Generators\ModelQueryGenerator;
use SimKlee\LaravelCraftingTable\Generators\ModelRepositoryGenerator;
use SimKlee\LaravelCraftingTable\Generators\ModelTestGenerator;
use SimKlee\LaravelCraftingTable\Models\Definitions\ModelDefinition;
use SimKlee\LaravelCraftingTable\Models\Definitions\ModelDefinitionBag;
use Str;

class ModelCrafterCommand extends AbstractCrafterCommand
{
    /** @var string */
    protected $signature = 'craft:model {name}';

    /** @var string */
    protected $description = '';

    private ModelDefinitionBag $bag;

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoDataTypeKeywordFoundException
     * @throws NoCastTypeForDataTypeException
     */
    public function __construct()
    {
        parent::__construct();

        $this->bag = new ModelDefinitionBag(config('models'));
    }

    public function handle(): int
    {
        $model           = $this->argument('name');
        $modelDefinition = $this->bag->get($model);

        dump($modelDefinition);

        $this->writeModelMigration($modelDefinition);
        $this->writeModel($modelDefinition);
        $this->writeModelQuery($modelDefinition);
        $this->writeModelRepository($modelDefinition);
        $this->writeModelTest($modelDefinition);

        return self::SUCCESS;
    }

    private function runCodeBeautifier(string $file): void
    {
        #$bin = './vendor/simklee/laravel-crafting-table/vendor/bin/phpcbf';
        $bin = './vendor/bin/phpcbf';
        exec(command: sprintf('%s %s > /dev/null 2>&1', $bin, $file));
    }

    private function writeModel(ModelDefinition $modelDefinition): void
    {
        $generator = new ModelGenerator($modelDefinition);
        $file      = sprintf('%s/%s.php', $generator->getClassPath(), $modelDefinition->model);
        $generator->write(file: $file, override: true);

        $this->runCodeBeautifier($file);
    }

    private function writeModelQuery(ModelDefinition $modelDefinition): void
    {
        $generator = new ModelQueryGenerator($modelDefinition);
        $file      = sprintf('%s/%sQuery.php', $generator->getClassPath(), $modelDefinition->model);
        $generator->write(file: $file, override: true);

        $this->runCodeBeautifier($file);
    }

    private function writeModelRepository(ModelDefinition $modelDefinition): void
    {
        $generator = new ModelRepositoryGenerator($modelDefinition);
        $file      = sprintf('%s/%sRepository.php', $generator->getClassPath(), $modelDefinition->model);
        $generator->write(file: $file, override: true);

        $this->runCodeBeautifier($file);
    }

    private function writeModelTest(ModelDefinition $modelDefinition): void
    {
        $generator = new ModelTestGenerator($modelDefinition);
        $file      = sprintf('%s/%sTest.php', $generator->getClassPath(), $modelDefinition->model);
        $generator->write(file: $file, override: true);

        $this->runCodeBeautifier($file);
    }

    private function writeModelMigration(ModelDefinition $modelDefinition): void
    {
        $generator = new ModelMigrationGenerator($modelDefinition);
        $datetime  = Carbon::now()->format('Y_m_d_His');
        $name      = sprintf('create_%s_table', Str::lower(Str::snake($modelDefinition->model)));
        $file      = sprintf('database/migrations/%s_%s.php', $datetime, $name);
        $generator->write(file: $file, override: true);

        $this->runCodeBeautifier($file);
    }
}
