<?php

use App\Common\Config\Definition\DbConfigDefinition;

return [
    'definition' => DbConfigDefinition::class,

    'database' => [
        'connections' => [
            'default' => [
                'driver'    => getenv('DB_DRIVER'),
                'host'      => getenv('DB_HOST'),
                'database'  => getenv('DB_NAME'),
                'username'  => getenv('DB_USER'),
                'password'  => getenv('DB_PASS'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],
        ],
    ],
];
