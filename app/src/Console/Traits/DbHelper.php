<?php

namespace App\Console\Traits;

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

trait DbHelper
{
    /**
     * Return class name by file basename
     * @param string $baseName
     *
     * @return string
     */
    private function getClassName($baseName)
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
     * @param string $tableName
     */
    private function safeCreateTable($tableName)
    {
        if (!Capsule::schema()->hasTable($tableName)) {
            Capsule::schema()->create($tableName, function($table) {
                $table->string('version');
                $table->timestamp('apply_time')->useCurrent();
                $table->primary('version');
            });
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

    /**
     * Run list of commands in files
     *
     * @param Finder          $files list of files to run
     * @param OutputInterface $output
     * @param string          $tableName
     * @param string          $method
     *
     * @return void
     */
    private function runActions(Finder $files, OutputInterface $output, $tableName, $method)
    {
        foreach ($files as $file) {
            $baseName = $file->getBasename('.php');
            $class    = $this->getClassName($baseName);

            if ($this->isRowExist($baseName, $tableName)) {
                $output->writeln([sprintf('`%s` - already exists.', $baseName)]);
                continue;
            }

            require_once($file);

            $obj = new $class();
            $obj->$method();

            $this->insertRow($baseName, $tableName);
            $output->writeln([sprintf('`%s` - done.', $baseName)]);
        }

        $output->writeln(['<info>Completed.</info>']);

        return;
    }
}
