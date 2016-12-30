<?php
$settings = require __DIR__ . '/../app/settings.php';

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