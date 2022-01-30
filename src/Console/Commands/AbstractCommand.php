<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    abstract public function handle(): int;

    protected function booleanChoice(string $question, bool $default = false): bool
    {
        return strtolower($this->choice($question, ['y' => 'yes', 'n' => 'no'], $default === true ? 'y' : 'n')) === 'y';
    }
}
