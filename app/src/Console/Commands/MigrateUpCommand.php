<?php

namespace App\Console\Commands;

use App\Console\Traits\DbHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MigrateCommand
 */
class MigrateUpCommand extends Command
{
    use DbHelper;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('migrate:up')
            ->setDescription('Command for run migration')
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runAction(MIGRATIONS_PATH, $output, $this->migrationsTable, 'up');

        return;
    }
}
