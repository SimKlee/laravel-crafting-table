<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Definitions;

class ColumnDefinition
{
    public const FOREIGN_KEY_TYPE_ONE_TO_ONE   = '1-1';
    public const FOREIGN_KEY_TYPE_ONE_TO_MANY  = '1-n';
    public const FOREIGN_KEY_TYPE_MANY_TO_MANY = 'n-m';

    public ?string $name             = null;
    public ?string $dataType         = null;
    public ?string $dataTypeCast     = null;
    public ?bool   $foreignKey       = false;
    public ?string $foreignKeyType   = null;
    public ?string $foreignKeyModel  = null;
    public ?string $foreignKeyColumn = null;
    public bool    $unsigned         = false;
    public bool    $nullable         = false;
    public bool    $autoIncrement    = false;
    public ?int    $length           = null;
    public ?int    $decimals         = null;
    public ?int    $precision        = null;

    public function toString(): string
    {
        $definitions = [];

        return implode('|', $definitions);
    }
}
