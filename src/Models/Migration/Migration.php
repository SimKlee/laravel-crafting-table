<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Models\Migration;

use Illuminate\Database\Schema\Blueprint;
use SimKlee\LaravelCraftingTable\Exceptions\ForeignKeyNameTooLongException;

class Migration extends \Illuminate\Database\Migrations\Migration
{
    /**
     * @throws ForeignKeyNameTooLongException
     */
    public function createForeignKey(Blueprint $blueprint, string $column, string $foreignModel, bool $withIndex = false, string $foreignKeyName = null)
    {
        if ($withIndex) {
            $blueprint->index([$column], $column);
        }

        if (is_null($foreignKeyName)) {
            $foreignKeyName = $this->getForeignKeyName($blueprint->getTable(), $column);
        }

        $blueprint->foreign($column, $foreignKeyName)
                  ->on($foreignModel::TABLE)
                  ->references($foreignModel::PROPERTY_ID);
    }

    public function createForeignKeys(Blueprint $blueprint, array $foreignKeys, bool $withIndex = true): void
    {
        collect($foreignKeys)->each(function (string $foreignModel, string $column) use ($blueprint, $withIndex) {
            $this->createForeignKey($blueprint, $column, $foreignModel, $withIndex);
        });
    }

    /**
     * @throws ForeignKeyNameTooLongException
     */
    public function dropForeignKey(Blueprint $blueprint, string $column): void
    {
        $blueprint->dropForeign($this->getForeignKeyName($blueprint->getTable(), $column));
    }

    protected function setTableComment(string $table, string $comment): void
    {
        \DB::statement(sprintf('ALTER TABLE `%s` comment "%s"', $table, $comment));
    }

    /**
     * @throws ForeignKeyNameTooLongException
     */
    public function getForeignKeyName(string $table, string $column): string
    {
        $name = sprintf('FK__%s__%s', $table, $column);

        if (strlen($name) > 64) {
            throw new ForeignKeyNameTooLongException(sprintf(
                'Foreign key name is longer than 64 chars. You have to specify manually! Failed name: %s (%s chars long). ' .
                'Perhaps you can use a acronym for the table name. But check if it is unique! (recommendation: %s)',
                $name,
                strlen($name),
                sprintf('fk__%s__%s', $this->getTableAcronym($table), $column)
            ));
        }

        return $name;
    }

    private function getTableAcronym(string $table): string
    {
        return implode(separator: '', array: array_map(
            callback: function (string $part) {
                return substr($part, 0, 1);
            },
            array: explode('_', $table)
        ));
    }
}
