<?php

require_once __DIR__.'/../app.paths.conf.php';
$settings = App\Common\Config\Settings::build();

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