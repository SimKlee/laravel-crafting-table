<?php

declare(strict_types=1);

namespace SimKlee\LaravelCraftingTable\Tests\Feature\Console\Commands;

use Illuminate\Console\Command;
use SimKlee\LaravelCraftingTable\Console\Commands\AbstractCommand;
use SimKlee\LaravelCraftingTable\Tests\Feature\FeatureTestCase;

class AbstractCommandTest extends FeatureTestCase
{
    public function testBooleanChoice()
    {
        $command = new class extends AbstractCommand {

            protected $signature = 'command:test';

            public function handle(): int
            {
                if ($this->booleanChoice('Is TRUE?')) {
                    return self::SUCCESS;
                } else {
                    return self::FAILURE;
                }
            }
        };

        $this->artisan('command:test')
            ->expectsChoice('Is TRUE?', 'y')
            ->assertExitCode(Command::SUCCESS);

        $this->artisan('command:test')
            ->expectsChoice('Is TRUE?', 'n')
            ->assertExitCode(Command::FAILURE);
    }
}
