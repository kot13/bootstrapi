<?php

namespace App\Console\Commands;

use App\Common\Helper;
use App\Console\Traits\CodeGenerate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * GenerateMigrationCommand
 */
class GenerateMigrationCommand extends Command
{
    use CodeGenerate;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:migration')
            ->setDescription('Generate new migration')
            ->setHelp('php partisan generate:migration</info>')
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<comment>Welcome to the migration generator</comment>']);

        $helper   = $this->getHelper('question');
        $question = new Question('<info>Please enter the name of the migration: </info>');
        $question->setValidator(function($answer) {
            if (strlen(trim($answer)) === 0) {
                throw new \RunTimeException('The name of the migration should be not empty');
            }

            return $answer;
        });

        $migrationName = $helper->ask($input, $output, $question);
        $baseName      = date('YmdHis').'_'.$migrationName.'.php';
        $path          = $this->getPath($baseName, MIGRATIONS_PATH);

        $placeHolders = [
            '<class>',
            '<tableName>',
        ];
        $replacements = [
            Helper::underscoreToCamelCase($migrationName, true),
            strtolower($migrationName),
        ];

        $this->generateCode($placeHolders, $replacements, 'MigrationTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new migration class to "<info>%s</info>"', realpath($path)));

        return;
    }
}
