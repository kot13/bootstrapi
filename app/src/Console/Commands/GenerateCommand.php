<?php

namespace App\Console\Commands;

use App\Common\Helper;
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
                $this->generateMigration($input, $output);
                break;
            case 'seed':
                $this->generateSeed($input, $output);
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
     * Generate migration
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    private function generateMigration(InputInterface $input, OutputInterface $output)
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
     * Generate seed
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    private function generateSeed(InputInterface $input, OutputInterface $output)
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
