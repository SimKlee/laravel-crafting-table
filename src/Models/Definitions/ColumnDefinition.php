<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

class ColumnDefinition
{
    public ?string $name             = null;
    public ?string $dataType         = null;
    public ?string $dataTypeCast     = null;
    public ?bool   $foreignKey       = false;
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
