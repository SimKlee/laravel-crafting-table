<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

class ModelCrafterCommand extends AbstractCrafterCommand
{
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
