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

$settings = require __DIR__ . '/../app/settings.php';

$app = new \Slim\App($settings);

// DIC configuration
$container = $app->getContainer();

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};

$app->get('/', function ($request, $response, $args) {
    $this->view->render($response, 'docs.twig');
    return $response;
});

$app->get('/content', function ($request, $response, $args) {
    $swagger = \Swagger\scan($this->settings['swagger']['baseDir']);
    header('Content-Type: application/json');
    echo $swagger;
});

// Run!
$app->run();