<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;
use Str;

class ColumnParser
{
    public const FOREIGN_KEY    = 'foreignKey';
    public const AUTO_INCREMENT = 'autoIncrement';

    private array            $keywords = [];
    private ColumnDefinition $columnDefinition;

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    public function __construct(string $name, string $definition)
    {
        $this->columnDefinition       = new ColumnDefinition();
        $this->columnDefinition->name = $name;
        $this->keywords               = $this->normalizeKeywords(explode('|', $definition));
        $this->parse();
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    private function parse(): void
    {
        $this->parseForeignKey();

        try {
            $dataTypeParser                       = new DataTypeParser($this->keywords);
            $this->columnDefinition->dataType     = $dataTypeParser->getDataType();
            $this->columnDefinition->dataTypeCast = $dataTypeParser->getCastType();
        } catch (NoDataTypeKeywordFoundException $e) {
            if ($this->columnDefinition->foreignKey === false) {
                throw $e;
            }
        }

        $this->columnDefinition->unsigned      = $this->keywordExists('unsigned');
        $this->columnDefinition->nullable      = $this->keywordExists('nullable');
        $this->columnDefinition->autoIncrement = $this->keywordExists(['autoincrement', 'autoIncrement', 'ai']);
        $this->columnDefinition->length        = $this->getIntegerValueFromKeyword('length');
        $this->columnDefinition->decimals      = $this->getIntegerValueFromKeyword('decimals');
        $this->columnDefinition->precision     = $this->getIntegerValueFromKeyword('precision');
    }

    private function parseForeignKey()
    {
        $this->columnDefinition->foreignKey = $this->keywordExists(['foreignKey', 'fk']);

        $parts = explode('_', $this->columnDefinition->name);
        $id    = array_pop($parts);
        $model = Str::ucfirst(Str::camel(implode('_', $parts)));

        $this->columnDefinition->foreignKeyModel  = $model;
        $this->columnDefinition->foreignKeyColumn = $id;
    }

    private function getForeignKeyColumnDefinition(string $column): ColumnDefinition
    {


        return $this->get(model: $model)->getColumn(name: $id);
    }

    private function normalizeKeywords(array $keywords): array
    {
        $normalizedKeywords = [];
        foreach ($keywords as $keyword) {
            $normalizedKeywords[] = match ($keyword) {
                'ai', 'autoincrement' => 'autoIncrement',
                default               => $keyword,
            };
        }

        return $normalizedKeywords;
    }

    private function getValueFromKeyword(string $keyword): string|null
    {
        foreach ($this->keywords as $key) {
            if (str_starts_with($key, $keyword) && str_contains($keyword, ':')) {
                return explode(':', $keyword)[1];
            }
        }

        return null;
    }

    private function keywordExists(string|array $keywords): bool
    {
        return collect($this->keywords)->filter(function (string $keyword) use ($keywords) {
                return (is_array($keywords) && in_array($keyword, $keywords)) || $keyword === $keywords;
            })->count() > 0;
    }

    private function getIntegerValueFromKeyword(string $keyword): int|null
    {
        $value = $this->getValueFromKeyword($keyword);

        return !is_null($value) ? (int)$value: null;
    }

    public function getColumnDefinition(): ColumnDefinition
    {
        return $this->columnDefinition;
    }
}
