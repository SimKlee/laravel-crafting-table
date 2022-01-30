<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

abstract class AbstractCrafterCommand extends AbstractCommand
{
    abstract public function handle(): int;
}
