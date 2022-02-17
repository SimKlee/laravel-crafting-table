<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Parser;

use SimKlee\LaravelCraftingTable\Exceptions\MissingForeignKeyTypeDefinition;
use SimKlee\LaravelCraftingTable\Exceptions\UnknownForeignKeyTypeDefinition;
use SimKlee\LaravelCraftingTable\Models\Definitions\ColumnDefinition;
use Str;

class ForeignKeyParser
{
    private ColumnDefinition $columnDefinition;
    private array            $definition;

    private array $availableTypes = [
        ColumnDefinition::FOREIGN_KEY_TYPE_ONE_TO_ONE,
        ColumnDefinition::FOREIGN_KEY_TYPE_ONE_TO_MANY,
        ColumnDefinition::FOREIGN_KEY_TYPE_MANY_TO_MANY,
    ];

    public function __construct(ColumnDefinition $columnDefinition, string $definition)
    {
        $this->columnDefinition = $columnDefinition;
        $this->definition       = explode('|', $definition);
    }

    /**
     * @throws MissingForeignKeyTypeDefinition
     * @throws UnknownForeignKeyTypeDefinition
     */
    public function parse(): ColumnDefinition
    {
        $this->columnDefinition->foreignKey = $this->isForeignKey();

        if ($this->columnDefinition->foreignKey) {
            $parts = explode('_', $this->columnDefinition->name);
            $id    = array_pop($parts);
            $model = Str::ucfirst(Str::camel(implode('_', $parts)));

            $this->columnDefinition->foreignKeyType   = $this->getForeignKeyType();
            $this->columnDefinition->foreignKeyModel  = $model;
            $this->columnDefinition->foreignKeyColumn = $id;
        }

        return $this->columnDefinition;
    }

    private function isForeignKey(): bool
    {
        $fkKeywords = ['foreignKey', 'fk'];

        return collect($this->definition)
                ->filter(function (string $definition) use ($fkKeywords) {
                    return in_array($definition, $fkKeywords);
                })->count() > 0;
    }

    /**
     * @throws MissingForeignKeyTypeDefinition
     * @throws UnknownForeignKeyTypeDefinition
     */
    private function getForeignKeyType(): string
    {
        $typeDefinition = collect($this->definition)->filter(function (string $definition) {
            return Str::startsWith(haystack: $definition, needles: 'type:');
        });

        if ($typeDefinition->count() !== 1) {
            throw new MissingForeignKeyTypeDefinition(sprintf(
                'Missing definition of foreign key type in "%s"', implode(separator: '|', array: $this->definition)
            ));
        }

        [, $type] = explode(separator: ':', string: $typeDefinition->first());

        if (!in_array(needle: $type, haystack: $this->availableTypes)) {
            throw new UnknownForeignKeyTypeDefinition(sprintf(
                'No known foreign key type found in "%s" [known types: %s]',
                $typeDefinition->first(),
                implode(separator: '|', array: $this->availableTypes)
            ));
        }

        return $type;
    }
}
