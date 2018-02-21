<?php

namespace App\Console\Commands;

use App\Console\Traits\CodeGenerate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * GenerateCommandCommand
 */
class GenerateCommandCommand extends Command
{
    use CodeGenerate;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:command')
            ->setDescription('Generate new command')
            ->setHelp('<info>php partisan generate:command</info>')
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<comment>Welcome to the command generator</comment>']);

        $helper   = $this->getHelper('question');
        $question = new Question('<info>Please enter the name of the command class: </info>', 'DefaultCommand');
        $question->setValidator(function($answer) {
            if ('Command' !== substr($answer, -7)) {
                throw new \RunTimeException('The name of the command should be suffixed with \'Command\'');
            }

            $baseName = $answer.'.php';
            if (true === file_exists($this->getPath($baseName, COMMANDS_PATH))) {
                throw new \RunTimeException('This command already exists');
            }

            return $answer;
        });

        $commandClass = $helper->ask($input, $output, $question);
        $commandName  = $this->colonize($commandClass);
        $baseName     = $commandClass.'.php';
        $path         = $this->getPath($baseName, COMMANDS_PATH);
        $placeHolders = [
            '<class>',
            '<name>',
        ];
        $replacements = [
            $commandClass,
            $commandName,
        ];

        $this->generateCode($placeHolders, $replacements, 'CommandTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new command class to "<info>%s</info>"', realpath($path)));

        return;
    }

    /**
     * Colonize command name
     *
     * @param $word
     * @return string
     */
    private function colonize($word)
    {
        $word = str_replace('Command', '', $word);

        return strtolower(preg_replace('/[^A-Z^a-z^0-9]+/', ':',
            preg_replace('/([a-zd])([A-Z])/', '\1:\2',
                preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1:\2', $word)))
        );
    }
}
