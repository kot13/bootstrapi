<?php

namespace App\Console\Commands;

use App\Console\Traits\DbHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MigrateDownCommand
 */
class MigrateDownCommand extends Command
{
    use DbHelper;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('migrate:down')
            ->setDescription('Command for rollback migration')
            ->addArgument('migration', InputArgument::REQUIRED, 'What kind of migration do you want to rollback?')
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
        $migrationName = $input->getArgument('migration');

        $file = MIGRATIONS_PATH.'/'.$migrationName.'.php';
        if (false === file_exists($file)) {
            throw new \RunTimeException('This migration not found');
        }

        $fileNamePieces = explode('_', $migrationName);

        require_once($file);

        $class = '';
        foreach ($fileNamePieces as $key => $item) {
            if ($key == 0) {
                continue;
            }
            $class .= ucfirst($item);
        }

        $obj = new $class();
        $obj->down();

        $this->deleteRow($migrationName, $this->migrationsTable);

        $output->writeln([sprintf('<info>Rollback `%s` migration - done</info>', $migrationName)]);
        return;
    }
}
