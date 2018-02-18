<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateCommand extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate new commands, models, schemas, migrations, seeds and docs')
            ->setHelp(<<<EOF
The <info>generate</info> command create new commands, models, schemas, migrations, seeds and docs.

<info>php partisan generate command|model|schema|migration|seed|docs</info>
EOF
            )
            ->addArgument('entity', InputArgument::REQUIRED, 'What do you want to generate?');
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
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var \App\Console\Partisan $app*/
        $app      = $this->getApplication();
        $settings = $app->container->get('settings');

        $what = $input->getArgument('entity');
        switch ($what) {
            case 'command':
                $this->generateCommand($input, $output);
                break;
            case 'model':
                break;
            case 'schema':
                break;
            case 'migration':
                break;
            case 'seed':
                break;
            case 'docs':
                $this->generateDocs($settings['params']['api'], $output);
                break;
            default:
                throw new \RunTimeException('This can not be generated');
        }

        return;
    }

    /**
     * @return bool|string
     */
    private function getTemplate()
    {
        $path = __DIR__ . '/../CodeTemplates/CommandTemplate.tpl';
        if (false === file_exists($path)) {
            throw new \RunTimeException('Not found template for command class');
        }

        return file_get_contents($path);
    }

    /**
     * Generate command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    private function generateCommand(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<comment>Welcome to the command generator</comment>']);

        $helper   = $this->getHelper('question');
        $question = new Question('<info>Please enter the name of the command class: </info>', 'DefaultCommand');
        $question->setValidator(function ($answer) {
            if ('Command' !== substr($answer, -7)) {
                throw new \RunTimeException('The name of the command should be suffixed with \'Command\'');
            }

            if (true === file_exists($this->getPathForCommand($answer))) {
                throw new \RunTimeException('This command already exists');
            }

            return $answer;
        });

        $commandClass = $helper->ask($input, $output, $question);
        $commandName  = $this->colonize($commandClass);
        $path         = $this->getPathForCommand($commandClass);

        $placeHolders = [
            '<class>',
            '<name>'
        ];
        $replacements = [
            $commandClass,
            $commandName
        ];

        $code = str_replace($placeHolders, $replacements, $this->getTemplate());

        file_put_contents($path, $code);

        $output->writeln(sprintf('Generated new command class to "<info>%s</info>"', realpath($path)));

        return;
    }

    /**
     * Generate documentation
     * @param string $apiUrl
     * @param OutputInterface $output
     */
    private function generateDocs($apiUrl, OutputInterface $output)
    {
        $apidocPath = CONFIG_PATH . '/apidoc.php';
        if (false === file_exists($apidocPath)) {
            throw new \RunTimeException(sprintf('The apidoc file `%s` not found', $apidocPath));
        };

        $path = APP_PATH;
        if (!is_writeable($path)) {
            throw new \RunTimeException(sprintf('The directory `%s` is not writeable', $path));
        }

        $baseName = $path . '/apidoc.json';
        $content  = require($apidocPath);

        $content['url']       = $apiUrl;
        $content['sampleUrl'] = $apiUrl;

        $content = json_encode($content);
        if (false === file_put_contents($baseName, $content)) {
            throw new \RunTimeException(sprintf('The file `%s` could not be written to', $baseName));
        };

        $output->writeln([exec('apidoc -i ./app -o ./docs -t ./docstemplate')]);

        return;
    }

    /**
     * @param string $commandClass
     * @return string
     * @throws \Exception
     */
    private function getPathForCommand($commandClass)
    {
        $dir = rtrim(COMMANDS_PATH, '/');
        if (!file_exists($dir)) {
            throw new \Exception(sprintf('Commands directory "%s" does not exist.', $dir));
        }

        return $dir . '/'.$commandClass.'.php';
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

        return  strtolower(preg_replace('/[^A-Z^a-z^0-9]+/',':',
            preg_replace('/([a-zd])([A-Z])/','\1:\2',
                preg_replace('/([A-Z]+)([A-Z][a-z])/','\1:\2',$word))));
    }
}
