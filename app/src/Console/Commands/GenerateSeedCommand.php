<?php

namespace App\Console\Commands;

use App\Common\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * GenerateSeedCommand
 */
class GenerateSeedCommand extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:seed')
            ->setDescription('Command generate:seed')
        ;
    }

    /**
     * Execute method of command
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<comment>Welcome to the seed generator</comment>']);

        $helper   = $this->getHelper('question');
        $question = new Question('<info>Please enter the name of the seed: </info>');
        $question->setValidator(function ($answer) {
            if (strlen(trim($answer)) === 0) {
                throw new \RunTimeException('The name of the seed should be not empty');
            }

            return $answer;
        });

        $seedName     = $helper->ask($input, $output, $question);
        $path         = $this->getPathForSeed($seedName);
        $placeHolders = [
            '<class>',
        ];
        $replacements = [
            Helper::underscoreToCamelCase($seedName, true),
        ];

        $this->generateCode($placeHolders, $replacements, 'SeedTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new seed class to "<info>%s</info>"', realpath($path)));

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
     * @param string $seedName
     * @return string
     * @throws \Exception
     */
    private function getPathForSeed($seedName)
    {
        $dir = rtrim(SEEDS_PATH, '/');
        if (!file_exists($dir)) {
            throw new \Exception(sprintf('Seed directory "%s" does not exist.', $dir));
        }

        $baseName = date('YmdHis') . '_' . $seedName . '.php';

        return $dir . '/' . $baseName;
    }
}
