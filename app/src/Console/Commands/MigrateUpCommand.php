<?php

namespace App\Console\Commands;

use App\Console\Traits\DbHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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
        if (!is_dir(MIGRATIONS_PATH) || !is_readable(MIGRATIONS_PATH)) {
            throw new \RunTimeException(sprintf('Migrations path `%s` is not good', MIGRATIONS_PATH));
        }

        $output->writeln([
            '<info>Run migrations</info>',
            sprintf('Ensure table `%s` presence', $this->migrationsTable)
        ]);

        try {
            $this->safeCreateTable($this->migrationsTable);
        } catch (\Exception $e) {
            $output->writeln([
                sprintf('Can\'t ensure table `%s` presence. Please verify DB connection params and presence of database named', $this->migrationsTable),
                sprintf('Error: `%s`', $e->getMessage()),
            ]);
        }

        $finder = new Finder();
        $finder->files()->name('*.php')->in(MIGRATIONS_PATH);

        $this->runActions($finder, $output, $this->migrationsTable, 'up');

        return;
    }
}
