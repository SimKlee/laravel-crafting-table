<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use SimKlee\LaravelCraftingTable\Models\Definitions\ModelDefinition;
use SimKlee\LaravelCraftingTable\Support\StringBuffer;
use Str;

abstract class AbstractGenerator
{
    protected ModelDefinition $modelDefinition;
    protected string          $template;
    protected bool            $class;
    protected string          $namespace;
    protected string          $extends = Model::class;
    protected Collection      $interfaces;
    protected Collection      $uses;
    protected Collection      $traits;

    public function __construct(ModelDefinition $modelDefinition)
    {
        $this->modelDefinition = $modelDefinition;
        $this->interfaces      = new Collection();
        $this->uses            = new Collection();
        $this->traits          = new Collection();
    }

    protected function beforeWrite(): void
    {

    }

    public function write(string $file, bool $override = false): bool
    {
        $this->beforeWrite();

        $buffer = StringBuffer::create()
                              ->appendIf($this->class, '<?php' . PHP_EOL . PHP_EOL)
                              ->append(view('crafting-table::' . $this->template)
                                  ->with('generator', $this)
                                  ->with('modelDefinition', $this->modelDefinition)
                                  ->render());

        $this->createDirectoryForFileIfNotExists($file);

        if (!File::exists($file) || $override) {
            return File::put($file, $buffer->toString()) !== false;
        }

        return false;
    }

    private function createDirectoryForFileIfNotExists(string $file): void
    {
        $directory = $this->getDirectoryFromFile($file);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
    }

    private function getDirectoryFromFile(string $file): string
    {
        $parts = explode(DIRECTORY_SEPARATOR, $file);
        array_pop($parts);

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    public function addUse(string $class)
    {
        $this->uses->add($class);
    }

    public function addUses(Collection|array $uses)
    {
        if (is_array($uses)) {
            $uses = collect($uses);
        }

        $uses->each(function (string $class) {
            $this->addUse($class);
        });
    }

    public function getUses(): Collection
    {
        return $this->uses->unique()->sort();
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getExtends(): string
    {
        return $this->extends;
    }

    public function setExtends(string $class, bool $use = true)
    {
        if ($use) {
            $this->extends = class_basename($class);
            $this->addUse($class);
        } else {
            $this->extends = $class;
        }
    }

    public function getClassPath(): string
    {
        return Str::replace('\\', '/', Str::replace(['App', 'Tests'], ['app', 'tests'], $this->namespace));
    }

}
