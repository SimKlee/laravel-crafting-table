<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use Illuminate\Console\Command;

abstract class AbstractCrafterCommand extends Command
{
    abstract public function handle(): int;
}
