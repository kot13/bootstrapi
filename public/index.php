<?php
if (php_sapi_name() == 'cli-server') {
    putenv('SECRET_KEY=test-key');
}

if (getenv('APPLICATION_ENV') == 'develop') {
    define('DEBUG_MODE', 1);
    error_reporting(E_ALL);
}

// Super debug func
function prr($value)
{
    if (defined('DEBUG_MODE') && DEBUG_MODE == 1) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    };
}

// Load all class
require __DIR__.'/../vendor/autoload.php';

// Load application settings
$settings = require __DIR__.'/../app/settings.php';

// Create container for application
$container = new \Slim\Container($settings);

// Register service providers & factories
$container->register(new \App\Providers\LogServiceProvider());
$container->register(new \App\Providers\RendererServiceProvider());
$container->register(new \App\Providers\AclServiceProvider());
$container->register(new \App\Providers\DatabaseServiceProvider());
$container->register(new \App\Providers\ValidationServiceProvider());
$container->register(new \App\Providers\MailerServiceProvider());
$container->register(new \App\Providers\ErrorHandlerServiceProvider());
$container->register(new \App\Providers\EncoderServiceProvider());

// Create new application
$app = new \Slim\App($container);

// Register middleware
$app->add(new \App\Middleware\Logger($app->getContainer()->get('logger')));
$app->add(new \App\Middleware\CustomException($app->getContainer()->get('apiRenderer')));

// Register routes
require __DIR__.'/../app/routes.php';

// Run!
$app->run();
