<?php

namespace App\Console\Commands;

use App\Console\Traits\DbHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SeedCommand
 */
class RunSeedCommand extends Command
{
    use DbHelper;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('run:seed')
            ->setDescription('Command for run seed')
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
        $this->runAction(SEEDS_PATH, $output, $this->seedsTable, 'run');

        return;
    }
}
