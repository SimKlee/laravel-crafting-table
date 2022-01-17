<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;

class ColumnParser
{
    private array  $keywords      = [];
    public ?string $dataType;
    public ?string $dataTypeCast;
    public bool    $nullable      = false;
    public bool    $autoIncrement = false;
    public ?int    $length;
    public ?int    $decimals;
    public ?int    $precision;

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    public function __construct(string $definition)
    {
        $this->keywords = explode('|', $definition);
        $this->parse();
    }

    /**
     * @throws MultipleDataTypeKeywordsFoundException
     * @throws NoCastTypeForDataTypeException
     * @throws NoDataTypeKeywordFoundException
     */
    private function parse(): void
    {
        $dataTypeParser      = new DataTypeParser($this->keywords);

        $this->dataType      = $dataTypeParser->getDataType();
        $this->dataTypeCast  = $dataTypeParser->getCastType();
        $this->nullable      = $this->keywordExists('nullable');
        $this->autoIncrement = $this->keywordExists(['autoincrement', 'autoIncrement', 'ai']);
        $this->length        = $this->getIntegerValueFromKeyword('length');
        $this->decimals      = $this->getIntegerValueFromKeyword('decimals');
        $this->precision     = $this->getIntegerValueFromKeyword('precision');
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
        collect($this->keywords)->filter(function (string $keyword) use ($keywords) {
            return (is_array($keywords) && in_array($keyword, $keywords)) || $keyword === $keywords;
        })->count() > 0;
    }

    private function getIntegerValueFromKeyword(string $keyword): int|null
    {
        $value = $this->getValueFromKeyword($keyword);

        return !is_null($value) ? (int)$value: null;
    }
}
