<?php

namespace App\Console\Commands;

use App\Console\Traits\DbHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir(SEEDS_PATH) || !is_readable(SEEDS_PATH)) {
            throw new \RunTimeException(sprintf('Seeds path `%s` is not good', SEEDS_PATH));
        }

        $output->writeln([
            '<info>Run seeds</info>',
            sprintf('Ensure table `%s` presence', $this->seedsTable)
        ]);

        try {
            $this->safeCreateTable($this->seedsTable);
        } catch (\Exception $e) {
            $output->writeln([
                sprintf('Can\'t ensure table `%s` presence. Please verify DB connection params and presence of database named', $this->seedsTable),
                sprintf('Error: `%s`', $e->getMessage()),
            ]);
        }

        $finder = new Finder();
        $finder->files()->name('*.php')->in(SEEDS_PATH);

        $this->runActions($finder, $output, $this->seedsTable, 'run');

        return;
    }
}
