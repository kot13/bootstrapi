<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;

use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

use App\Common\Acl;
use App\Common\Renderer;

use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Document\Error;

// DIC configuration
$container = $app->getContainer();

// render
$container['renderer'] = function($c){
    $view = new Renderer();
    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Logger($settings['logger']['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['logger']['path'], Logger::DEBUG));
    return $logger;
};

// error handlers
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $error = new Error(
            null,
            null,
            '500',
            '500',
            'Internal server error',
            'Internal server error'
        );

        $result = Encoder::instance()->encodeError($error);

        return $c->get('renderer')->jsonApiRender($c->get('response'), 500, $result);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        $error = new Error(
            null,
            null,
            '405',
            '405',
            'Method Not Allowed',
            'Method must be one of: ' . implode(', ', $methods)
        );

        $result = Encoder::instance()->encodeError($error);

        return $c->get('renderer')->jsonApiRender($c->get('response'), 405, $result);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $error = new Error(
            null,
            null,
            '404',
            '404',
            'Not found',
            'Entity not found'
        );

        $result = Encoder::instance()->encodeError($error);

        return $c->get('renderer')->jsonApiRender($c->get('response'), 404, $result);
    };
};

// database
$setting = $container->get('settings');
$capsule = new Capsule;
$capsule->addConnection($setting['database']['connections']['main'], 'default');
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// ACL
$container['acl'] = function ($c) {
    $settings = $c->get('settings');
    $acl = new Acl($settings['acl']);

    return $acl;
};

// translation
$container['translator'] = function($c){
    $settings = $c->get('settings');

    $translation_file_loader = new FileLoader(new Filesystem, $settings['translate']['path']);
    $translator = new Translator($translation_file_loader, $settings['translate']['locale']);

    return $translator;
};

// validation
$container['validation'] = function($c){
    $translator = $c->get('translator');
    $validation = new Factory($translator);

    return $validation;
};

// mailer
$container['mailer'] = function($c){
    $settings = $c->get('settings');
    $transport = \Swift_MailTransport::newInstance();
    $mailer = \Swift_Mailer::newInstance($transport);

    return $mailer;
};