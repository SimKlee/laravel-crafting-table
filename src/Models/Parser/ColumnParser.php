<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Parser;

use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;
use SimKlee\LaravelCraftingTable\Models\Definitions\ColumnDefinition;
use Str;

class ColumnParser
{
    public const FOREIGN_KEY    = 'foreignKey';
    public const AUTO_INCREMENT = 'autoIncrement';

    private array            $keywords = [];
    private string           $definition;
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
        $this->definition             = $definition;
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
        $foreignKeyParser       = new ForeignKeyParser(
            columnDefinition: $this->columnDefinition,
            definition: $this->definition
        );
        $this->columnDefinition = $foreignKeyParser->parse();

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
            if (str_starts_with(haystack: $key, needle: $keyword) && str_contains(haystack: $key, needle: ':')) {
                return explode(':', $key)[1];
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
