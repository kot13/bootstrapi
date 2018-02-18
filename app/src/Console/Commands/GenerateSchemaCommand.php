<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateSchemaCommand
 */
class GenerateSchemaCommand extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:schema')
            ->setDescription('Command generate:schema')
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<info>Execute</info>']);
    }
}
