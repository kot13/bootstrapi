<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Finder\Finder;

/**
 * MigrateCommand
 */
class MigrateCommand extends Command
{
    /**
     * @var string Table name where migrations info is kept
     */
    const MIGRATIONS_TABLE = 'migrations';

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('migrate')
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

        $output->writeln(['<info>Run migrations</info>']);
        $this->safeCreateTable(self::MIGRATIONS_TABLE, $output);

        $finder = new Finder();
        $finder->files()->name('*.php')->in(MIGRATIONS_PATH);

        $this->runActionFiles($finder, $output);

        return;
    }

    /**
     * Run list of migrations in files
     *
     * @param Finder          $files list of files to run
     * @param OutputInterface $output
     *
     * @return void
     */
    private function runActionFiles(Finder $files, OutputInterface $output)
    {
        foreach ($files as $file) {
            $baseName = $file->getBasename('.php');
            $class    = $this->getMigrationClass($baseName);

            if ($this->isRowExist($baseName, self::MIGRATIONS_TABLE)) {
                $output->writeln([sprintf('`%s` - already exists.', $baseName)]);
                continue;
            }

            require_once($file);

            $obj = new $class();
            $obj->up();

            $this->insertRow($baseName, self::MIGRATIONS_TABLE);
            $output->writeln([sprintf('`%s` - done.', $baseName)]);
        }

        $output->writeln(['<info>Completed.</info>']);

        return;
    }

    /**
     * Return class name by file basename
     * @param string $baseName
     *
     * @return string
     */
    private function getMigrationClass($baseName)
    {
        $filenameParts = explode('_', $baseName);
        $class         = '';

        array_shift($filenameParts);

        foreach ($filenameParts as $key => $filenamePart) {
            $class .= ucfirst($filenamePart);
        }

        return $class;
    }

    /**
     * @param string          $tableName
     * @param OutputInterface $output
     */
    private function safeCreateTable($tableName, OutputInterface $output)
    {
        $output->writeln([sprintf('Ensure table `%s` presence', $tableName)]);

        try {
            if (!Capsule::schema()->hasTable($tableName)) {
                Capsule::schema()->create($tableName, function($table) {
                    $table->string('version');
                    $table->timestamp('apply_time')->useCurrent();
                    $table->primary('version');
                });
            }
        } catch (\Exception $e) {
            $output->writeln([
                sprintf('Can\'t ensure table `%s` presence. Please verify DB connection params and presence of database named', $tableName),
                sprintf('Error: `%s`', $e->getMessage()),
            ]);
        }
    }

    /**
     * @param $name
     * @param $table
     * @return bool
     */
    private function isRowExist($name, $table)
    {
        $item = Capsule::table($table)->where('version', $name)->first();
        return !is_null($item);
    }

    /**
     * @param $name
     * @param $table
     */
    private function insertRow($name, $table)
    {
        Capsule::table($table)->insert([
            'version' => $name,
        ]);
    }
}
