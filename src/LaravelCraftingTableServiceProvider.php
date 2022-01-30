<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable;

use Illuminate\Support\ServiceProvider;
use SimKlee\LaravelCraftingTable\Console\Commands\InstallCommand;
use SimKlee\LaravelCraftingTable\Console\Commands\ModelCrafterCommand;

class LaravelCraftingTableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallCommand::class,
                ModelCrafterCommand::class,
            ]);

        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'crafting-table');
    }

    public function register(): void
    {

    }
}
