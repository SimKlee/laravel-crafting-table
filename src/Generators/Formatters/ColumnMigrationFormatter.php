<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Generators\Formatters;

use SimKlee\LaravelCraftingTable\Exceptions\MissingMethodInMapForFormatterException;
use SimKlee\LaravelCraftingTable\Models\Definitions\ColumnDefinition;
use SimKlee\LaravelCraftingTable\Support\StringBuffer;
use Str;

class ColumnMigrationFormatter
{
    private string           $model;
    private ColumnDefinition $columnDefinition;

    private array $methodMap = [
        'tinyInteger'   => 'tinyInteger',
        'smallInteger'  => 'smallInteger',
        'mediumInteger' => 'mediumInteger',
        'integer'       => 'integer',
        'bigInteger'    => 'bigInteger',
        'string'        => 'string',
        'char'          => 'char',
        'date'          => 'date',
        'time'          => 'time',
        'dateTime'      => 'dateTime',
        'timestamp'     => 'timestamp',
        'decimal'       => 'decimal',
    ];


    /**
     * @throws MissingMethodInMapForFormatterException
     */
    public function __construct(string $model, ColumnDefinition $columnDefinition)
    {
        $this->model            = $model;
        $this->columnDefinition = $columnDefinition;

        if (!isset($this->methodMap[ $columnDefinition->dataType ])) {
            throw new MissingMethodInMapForFormatterException(sprintf('Unknown migration method for data type "%s"', $columnDefinition->dataType));
        }
    }

    /**
     * @throws MissingMethodInMapForFormatterException
     */
    public static function create(string $model, ColumnDefinition $columnDefinition): ColumnMigrationFormatter
    {
        return new ColumnMigrationFormatter(model: $model, columnDefinition: $columnDefinition);
    }

    public function toString(): string
    {
        $buffer = new StringBuffer('$table->');

        switch ($this->columnDefinition->dataTypeCast) {
            case 'int':
                $method = $this->methodMap[ $this->columnDefinition->dataType ];
                $buffer->appendIf(condition: $this->columnDefinition->unsigned, string: 'unsigned')
                       ->appendIf(
                           condition: $this->columnDefinition->unsigned,
                           string: Str::ucfirst($method),
                           else: $method
                       )
                       ->append('(')
                       ->appendFormatted('column: %s::PROPERTY_%s', $this->model, Str::upper($this->columnDefinition->name))
                       ->appendIf(condition: $this->columnDefinition->autoIncrement, string: ', autoIncrement: true')
                       ->append(')');
                break;

            case 'string':
                $buffer->append($this->methodMap[ $this->columnDefinition->dataType ])
                       ->append('(')
                       ->appendFormatted('column: %s::PROPERTY_%s', $this->model, Str::upper($this->columnDefinition->name))
                       ->appendIfNotNull(value: $this->columnDefinition->length, string: ', length: ' . $this->columnDefinition->length)
                       ->append(')');
                break;

            case 'float':
                $buffer->append($this->methodMap[ $this->columnDefinition->dataType ])
                       ->append('(')
                       ->appendFormatted('column: %s::PROPERTY_%s', $this->model, Str::upper($this->columnDefinition->name))
                       ->appendIfNotNull(value: $this->columnDefinition->length, string: ', total: ' . $this->columnDefinition->length)
                       ->appendIfNotNull(value: $this->columnDefinition->precision, string: ', places: ' . $this->columnDefinition->precision)
                       ->appendIf(condition: $this->columnDefinition->unsigned, string: ', unsigned: true', else: ', unsigned: false')
                       ->append(')');
                break;
        };

        $buffer->appendIf($this->columnDefinition->nullable, '->nullable()')
               ->append(';');

        return $buffer->toString();
    }
}
