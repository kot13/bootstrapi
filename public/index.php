<?php

require_once __DIR__.'/../bootstrap/app.php';

// Create new application
$app = new \Slim\App($container);

// Register middleware
$app->add(new \App\Middleware\CustomException($app->getContainer()->get('apiRenderer')));
$app->add(new \App\Middleware\Logger($app->getContainer()->get('logger')));
$app->add(new \RKA\Middleware\IpAddress());

// Register routes
require __DIR__.'/../app/routes.php';

// Run!
$app->run();
