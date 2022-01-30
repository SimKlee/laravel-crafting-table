<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators;

use File;
use Illuminate\Support\Collection;
use SimKlee\LaravelCraftingTable\Models\ModelDefinition;

abstract class AbstractGenerator
{
    protected ModelDefinition $modelDefinition;
    protected string          $template;
    protected bool            $class;
    protected string          $namespace;
    protected string          $extends;
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

    public function write(string $file, bool $override = false): bool
    {
        $content = view('crafting-table::' . $this->template)
            ->with('generator', $this)
            ->with('modelDefinition', $this->modelDefinition)
            ->render();

        if ($this->class) {
            $content = '<?php' . PHP_EOL . PHP_EOL . $content;
        }

        $directory = $this->getDirectoryFromFile($file);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!File::exists($file) || $override) {
            return File::put($file, $content) !== false;
        }

        return false;
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

    public function addUses(Collection $uses)
    {
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

}
