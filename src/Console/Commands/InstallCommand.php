<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected const OPTION_DEV = 'dev';

    /** @var string */
    protected $signature = 'crafter:install {--dev}';

    /** @var string */
    protected $description = '';

    public function handle(): int
    {
        $result = self::SUCCESS;

        $this->createModelConfig();

        if ($this->option(self::OPTION_DEV)) {
            if ($this->handleDev() === self::FAILURE) {
                $result = self::FAILURE;
            }
        }

        return $result;
    }

    private function handleDev(): int
    {
        return self::SUCCESS;
    }

    private function createModelConfig()
    {

    }
}
