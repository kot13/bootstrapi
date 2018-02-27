<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateDocsCommand
 */
class GenerateDocsCommand extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:docs')
            ->setDescription('Generate documentation for api')
            ->setHelp('<info>php partisan generate docs</info>')
        ;
    }

    /**
     * Execute method of command
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var \App\Console\Partisan $app */
        $app      = $this->getApplication();
        $settings = $app->container->get('settings');

        $apidocPath = CONFIG_PATH.'/apidoc.php';
        if (false === file_exists($apidocPath)) {
            throw new \RunTimeException(sprintf('The apidoc file `%s` not found', $apidocPath));
        };

        $path = APP_PATH;
        if (!is_writeable($path)) {
            throw new \RunTimeException(sprintf('The directory `%s` is not writeable', $path));
        }

        $baseName = $path.'/apidoc.json';
        $content  = require($apidocPath);

        $content['url']       = $settings['params']['api'];
        $content['sampleUrl'] = $settings['params']['api'];

        $content = json_encode($content);
        if (false === file_put_contents($baseName, $content)) {
            throw new \RunTimeException(sprintf('The file `%s` could not be written to', $baseName));
        };

        $output->writeln([exec('apidoc -i ./app -o ./docs -t ./docstemplate')]);

        return;
    }
}
