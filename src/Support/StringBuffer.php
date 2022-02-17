<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Support;

class StringBuffer
{
    private string $string = '';

    public function __construct(string $string = null)
    {
        if (!is_null($string)) {
            $this->string = $string;
        }
    }

    public static function create(string $string = null): StringBuffer
    {
        return new StringBuffer($string);
    }

    public function append(string $string): StringBuffer
    {
        $this->string .= $string;

        return $this;
    }

    public function appendFormatted(string $format, ...$values): StringBuffer
    {
        $this->string .= sprintf($format, ...$values);

        return $this;
    }

    public function appendIf(bool $condition, string $string, string $else = null): StringBuffer
    {
        if ($condition) {
            $this->append($string);
        } elseif (!is_null($else)) {
            $this->append($else);
        }

        return $this;
    }

    public function appendIfNot(bool $condition, string $string, string $else = null): StringBuffer
    {
        return $this->appendIf(!$condition, $string, $else);
    }

    public function appendIfNull($value, string $string, string $else = null): StringBuffer
    {
        return $this->appendIf(is_null($value), $string, $else);
    }

    public function appendIfNotNull($value, string $string, string $else = null): StringBuffer
    {
        return $this->appendIf(!is_null($value), $string, $else);
    }

    public function toString(): string
    {
        return $this->string;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
