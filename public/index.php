<?php
// Load all class
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app.paths.conf.php';

// Create container for application
$container = new \Slim\Container(App\Common\Config\Settings::build());

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
