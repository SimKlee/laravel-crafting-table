<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models;

class ColumnDefinition
{
    public ?string $name;
    public ?string $dataType;
    public ?string $dataTypeCast;
    public bool    $unsigned      = false;
    public bool    $nullable      = false;
    public bool    $autoIncrement = false;
    public ?int    $length;
    public ?int    $decimals;
    public ?int    $precision;

    public function toString():string
    {
        $definitions = [];

        return implode('|', $definitions);
    }
}
