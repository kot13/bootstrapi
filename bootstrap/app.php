<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app.paths.conf.php';

// Create container for application based on settings
try {
    $settings = App\Common\Config\Settings::build();
} catch (Exception $e) {
    exit($e->getMessage().PHP_EOL);
}
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
