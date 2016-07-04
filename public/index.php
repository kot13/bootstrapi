<?php
if (getenv('APPLICATION_ENV') == 'develop') {
    define('DEBUG_MODE', 1);
    error_reporting(E_ALL);
}

// Super debug func
function prr($value){
    if (defined('DEBUG_MODE') && DEBUG_MODE == 1) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    };
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

$settings = require __DIR__ . '/../app/settings.php';

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register middleware
require __DIR__ . '/../app/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run!
$app->run();