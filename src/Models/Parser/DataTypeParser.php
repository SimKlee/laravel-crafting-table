<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

use SimKlee\LaravelCraftingTable\Exceptions\MultipleDataTypeKeywordsFoundException;
use SimKlee\LaravelCraftingTable\Exceptions\NoCastTypeForDataTypeException;
use SimKlee\LaravelCraftingTable\Exceptions\NoDataTypeKeywordFoundException;

class DataTypeParser
{
    private ?string $dataType;

    private array $typeMap = [
        'tinyinteger'   => 'tinyInteger',
        'tinyint'       => 'tinyInteger',
        'smallinteger'  => 'smallInteger',
        'smallint'      => 'smallInteger',
        'mediuminteger' => 'mediumInteger',
        'mediumint'     => 'mediumInteger',
        'integer'       => 'integer',
        'int'           => 'integer',
        'biginteger'    => 'bigInteger',
        'bigint'        => 'bigInteger',
        'varchar'       => 'string',
        'string'        => 'string',
        'char'          => 'char',
        'timestamp'     => 'timestamp',
    ];

    private array $castMap = [
        'int'     => [
            'tinyInteger',
            'smallInteger',
            'mediumInteger',
            'integer',
            'bigInteger',
        ],
        'string'  => [
            'string',
            'varchar',
            'char',
        ],
        'array'   => [
            'json',
        ],
        'boolean' => [
            'boolean',
        ],
        'Carbon'  => [
            'date',
            'datetime',
            'timestamp',
            'time',
            'year',
        ],
    ];

    /**
     * @throws MultipleDataTypeKeywordsFoundException|NoDataTypeKeywordFoundException
     */
    public function __construct(array $keywords)
    {
        $keyword = collect($keywords)->filter(function (string $keyword) {
            return in_array(strtolower($keyword), $this->typeMap);
        });

        if ($keyword->count() === 1) {
            $this->handleKeyword($keyword->first());
        } elseif ($keyword->count() > 1) {
            throw new MultipleDataTypeKeywordsFoundException($keyword->implode(', '));
        } elseif ($keyword->count() === 0) {
            throw new NoDataTypeKeywordFoundException(implode(', ', $keywords));
        }
    }

    private function handleKeyword(string $keyword): void
    {
        $this->dataType = $this->typeMap[ $keyword ];
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @throws NoCastTypeForDataTypeException
     */
    public function getCastType(): string
    {
        foreach ($this->castMap as $castType => $dataTypes) {
            if (in_array($this->dataType, $dataTypes)) {
                return $castType;
            }
        }

        throw new NoCastTypeForDataTypeException($this->dataType);
    }
}
