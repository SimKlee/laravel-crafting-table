<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use SimKlee\LaravelCraftingTable\Exceptions\UnknownForeignKeyColumnNameSyntaxException;

class ForeignKeyParser
{
    private ModelDefinitionBag $bag;
    private string             $model;
    private string             $column;
    private string             $definition;

    private ?string $referencedModel  = null;
    private ?string $referencedColumn = 'id';

    /**
     * @throws UnknownForeignKeyColumnNameSyntaxException
     */
    public function __construct(ModelDefinitionBag $bag, string $model, string $column, string $definition)
    {
        $this->bag        = $bag;
        $this->model      = $model;
        $this->column     = $column;
        $this->definition = $this->getDefinitionWithoutForeignKeyKeyword($definition);

        $this->mergeDefinitions();
    }

    private function getDefinitionWithoutForeignKeyKeyword(string $definition): string
    {
        $definitions     = [];
        $definitionParts = explode('|', $definition);
        foreach ($definitionParts as $definitionPart) {
            if (in_array($definitionPart, ['fk', 'FK', 'foreignKey'])) {
                continue;
            }

            $definitions[] = $definitionPart;
        }

        return implode('|', $definitions);
    }

    /**
     * @throws UnknownForeignKeyColumnNameSyntaxException
     */
    private function mergeDefinitions(): void
    {
        $columnParts = explode('_', $this->column);
        if (last($columnParts) !== 'id') {
            throw new UnknownForeignKeyColumnNameSyntaxException(sprintf('Model %s: %s', $this->model, $this->column));
        }

        $this->referencedColumn = array_pop($columnParts);
        $modelName              = implode('', array_map(function (string $part) {
            return ucfirst($part);
        }, $columnParts));

        $this->referencedModel = ($modelName === 'Parent') ? $this->model: $modelName;

        /** @var ColumnDefinition $columnDefinition */
        $columnDefinition = $this->bag->get($this->referencedModel)->columns->get($this->referencedColumn);

        // @TODO: merge definitions
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }
}
