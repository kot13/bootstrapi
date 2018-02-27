<?php

namespace App\Console\Commands;

use App\Common\Helper;
use App\Console\Traits\CodeGenerate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * GenerateSeedCommand
 */
class GenerateSeedCommand extends Command
{
    use CodeGenerate;

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
        $question->setValidator(function($answer) {
            if (strlen(trim($answer)) === 0) {
                throw new \RunTimeException('The name of the seed should be not empty');
            }

            return $answer;
        });

        $seedName     = $helper->ask($input, $output, $question);
        $baseName     = date('YmdHis').'_'.$seedName.'.php';
        $path         = $this->getPath($baseName, SEEDS_PATH);
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
}
