<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
