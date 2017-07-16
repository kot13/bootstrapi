<?php

return [
    // monolog settings
    'logger' => [
        'name'  => 'app',
        'path'  => __DIR__.'/../log/app.log',
        'level' => Monolog\Logger::DEBUG,
    ],
];