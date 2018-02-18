<?php

namespace App\Console\Commands;

use App\Common\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * GenerateMigrationCommand
 */
class GenerateMigrationCommand extends Command
{
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
        $question->setValidator(function ($answer) {
            if (strlen(trim($answer)) === 0) {
                throw new \RunTimeException('The name of the migration should be not empty');
            }

            return $answer;
        });

        $migrationName = $helper->ask($input, $output, $question);
        $path          = $this->getPathForMigration($migrationName);
        $placeHolders  = [
            '<class>',
            '<tableName>',
        ];
        $replacements  = [
            Helper::underscoreToCamelCase($migrationName, true),
            strtolower($migrationName),
        ];

        $this->generateCode($placeHolders, $replacements, 'MigrationTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new migration class to "<info>%s</info>"', realpath($path)));

        return;
    }

    /**
     * Generate code
     *
     * @param array  $placeHolders
     * @param array  $replacements
     * @param string $templateName
     * @param string $resultPath
     * @return bool|int
     */
    public function generateCode($placeHolders, $replacements, $templateName, $resultPath)
    {
        $templatePath = CODE_TEMPLATE_PATH . '/' . $templateName;
        if (false === file_exists($templatePath)) {
            throw new \RunTimeException(sprintf('Not found template %s', $templatePath));
        }

        $template = file_get_contents($templatePath);

        $code = str_replace($placeHolders, $replacements, $template);

        return file_put_contents($resultPath, $code);
    }

    /**
     * @param string $migrationName
     * @return string
     * @throws \Exception
     */
    private function getPathForMigration($migrationName)
    {
        $dir = rtrim(MIGRATIONS_PATH, '/');
        if (!file_exists($dir)) {
            throw new \Exception(sprintf('Migration directory "%s" does not exist.', $dir));
        }

        $baseName = date('YmdHis') . '_' . $migrationName . '.php';

        return $dir . '/' . $baseName;
    }
}
