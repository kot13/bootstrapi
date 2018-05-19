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
 * GenerateSchemaCommand
 */
class GenerateSchemaCommand extends Command
{
    use CodeGenerate;

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate:schema')
            ->setDescription('Command for generate schema')
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
        $output->writeln(['<comment>Welcome to the schema generator</comment>']);

        $helper    = $this->getHelper('question');
        $question  = new Question('<info>Please enter table name: </info>');
        $tableName = $helper->ask($input, $output, $question);
        $tableInfo = Capsule::schema()->getColumnListing($tableName);
        if (count($tableInfo) === 0) {
            $output->writeln([sprintf('<comment>Not found table `%s`</comment>', $tableName)]);
            return;
        }

        $columns = $this->getColumnsInfo($tableInfo, $tableName);

        $modelName    = substr($tableName, 0, -1);
        $className    = Helper::underscoreToCamelCase($modelName, true).'Schema';
        $baseName     = $className.'.php';
        $path         = $this->getPath($baseName, SCHEMAS_PATH);
        $resourceType = str_replace('_', '-', strtolower($modelName));

        $placeHolders = [
            '<class>',
            '<resourceType>',
            '<resourceTypeInCamelCase>',
            '<attributes>',
            '<params>',
            '<attributesToClass>',
        ];
        $replacements = [
            $className,
            str_replace('_', '-', strtolower($modelName)),
            Helper::dashesToCamelCase($resourceType, true),
            $this->generateAttributes($columns),
            $this->generateParams($columns),
            $this->generateAttributesToClass($columns),
        ];

        $this->generateCode($placeHolders, $replacements, 'SchemaTemplate.tpl', $path);

        $output->writeln(sprintf('Generated new schema class to "<info>%s</info>"', realpath($path)));

        return;
    }

    /**
     * @param array  $tableInfo
     * @param string $tableName
     * @return array
     */
    private function getColumnsInfo($tableInfo, $tableName)
    {
        $columns = [];
        foreach ($tableInfo as $columnName) {
            $columnType = Capsule::schema()->getColumnType($tableName, $columnName);

            $columns[] = [
                'name' => $columnName,
                'type' => $columnType,
                'fake' => $this->getFakeData($columnType),
            ];
        }

        return $columns;
    }

    /**
     * Return fake data for examples
     * @param $columnType
     * @return string
     */
    private function getFakeData($columnType)
    {
        switch ($columnType) {
            case 'string':
            case 'text':
                $fake = '"String"';
                break;
            case 'integer':
                $fake = '1';
                break;
            case 'decimal':
                $fake = '1.0';
                break;
            case 'datetime':
                $fake = '"2016-10-17T07:38:21+0000"';
                break;
            default:
                $fake = '';
        }

        return $fake;
    }

    /**
     * @param array $columns
     * @return string
     */
    private function generateAttributes($columns)
    {
        $attributes = [];
        $counter = 1;
        foreach ($columns as $column) {
            if ($column['name'] === 'id') {
                continue;
            }
            $counter++;
            if (count($columns) !== $counter) {
                $attributes[] = sprintf(' *             "%s": %s,', $column['name'], $column['fake']);
            } else {
                $attributes[] = sprintf(' *             "%s": %s', $column['name'], $column['fake']);
            }
        };

        return implode("\n", $attributes);
    }

    /**
     * @param array $columns
     * @return string
     */
    private function generateParams($columns)
    {
        $params = [];
        foreach ($columns as $column) {
            if ($column['name'] === 'id') {
                continue;
            }

            $params[] = sprintf(' * @apiParam {%s} %s', ucfirst($column['type']), $column['name']);
        };

        return implode("\n", $params);
    }

    /**
     * @param array $columns
     * @return string
     */
    private function generateAttributesToClass($columns)
    {
        $attributes = [];
        foreach ($columns as $column) {
            if ($column['name'] === 'id') {
                continue;
            }
            if ($column['type'] === 'datetime') {
                $attributes[] = sprintf("            '%s' => Carbon::parse(\$entity->%s)->setTimezone('UTC')->format(Carbon::ISO8601),", $column['name'], $column['name']);
            } else {
                $attributes[] = sprintf("            '%s' => (%s)\$entity->%s,", $column['name'], $column['type'], $column['name']);
            }
        };

        return implode("\n", $attributes);
    }
}
