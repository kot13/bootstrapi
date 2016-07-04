<?php
namespace App\Commands;

final class SampleController
{
    private $args;

    public function __construct($args)
    {
        $this->args = $args;
    }

    public function actionIndex()
    {
        echo "This is sample console command." . PHP_EOL;
        echo "Actions: " . PHP_EOL;
        echo 'partisan sample index' . PHP_EOL;
    }
}