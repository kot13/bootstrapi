<?php

namespace App\Console\Commands;

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Common\Helper;
use App\Console\Traits\CodeGenerate;

/**
 * GenerateModelCommand
 */
class GenerateModelCommand extends Command
{
    use CodeGenerate;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:model')
            ->setDescription('Command for generate model')
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
        $output->writeln(['<comment>Welcome to the model generator</comment>']);

        $helper    = $this->getHelper('question');
        $question  = new Question('<info>Please enter table name: </info>');
        $tableName = $helper->ask($input, $output, $question);

        $table = Capsule::schema()->getColumnListing($tableName);
        if (count($table) === 0) {
            $output->writeln([sprintf('<comment>Not found table %s</comment>', $tableName)]);
        }

        $columns = [];
        foreach ($table as $columnName) {
            $columnType = Capsule::schema()->getColumnType($tableName, $columnName);
            $columns[] = [
                'name' => $columnName,
                'type' => $columnType !== 'datetime' ? $columnType : '\Carbon\Carbon',
            ];
        }

        $modelName = substr($tableName, 0, -1);
        $className = Helper::underscoreToCamelCase($modelName, true);
        $baseName  = $className.'.php';
        $path      = $this->getPath($baseName, MODELS_PATH);

        $placeHolders = [
            '<class>',
            '<tableName>',
            '<phpdoc>',
            '<fillable>',
        ];
        $replacements = [
            $className,
            strtolower($tableName),
            $this->generatePhpDoc($columns),
            $this->generateFillable($columns),
        ];

        $this->generateCode($placeHolders, $replacements, 'ModelTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new model class to "<info>%s</info>"', realpath($path)));

        return;
    }

    /**
     * @param array $columns
     * @return string
     */
    private function generatePhpDoc($columns)
    {
        $phpdoc = [];
        foreach ($columns as $column) {
            $phpdoc[] = sprintf(" * @property %s\t$%s", $column['type'], $column['name']);
        };

        return implode("\n", $phpdoc);
    }

    /**
     * @param array $columns
     * @return string
     */
    private function generateFillable($columns)
    {
        $fillable = [];
        foreach ($columns as $column) {
            $fillable[] = sprintf("        '%s',", $column['name']);
        };

        return implode("\n", $fillable);
    }
}
