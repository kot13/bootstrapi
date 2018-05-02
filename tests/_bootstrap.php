<?php

require_once __DIR__.'/../app.paths.conf.php';

try {
    $settings = App\Common\Config\Settings::build();
} catch (Exception $e) {
    exit($e->getMessage().PHP_EOL);
}

\Codeception\Configuration::append([
    'modules' => [
        'enabled' => [
            [
                'REST' => [
                    'depends' => 'PhpBrowser',
                    'url'     => $settings['settings']['params']['host'],
                ]
            ]
        ]
    ]
]);
