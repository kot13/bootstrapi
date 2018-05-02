<?php

return [
    'params' => [
        'host'      => getenv('APP_HOST'),
        'api'       => getenv('APP_API_HOST'),
        'env'       => getenv('APP_ENV'),
        'uploadDir' => __DIR__.'/../public/uploads/',
    ],

    'accessToken' => [
        'ttl'        => 3600,
        'iss'        => null,
        'secretKey'  => getenv('AUTH_SECRET_KEY'),
        'allowHosts' => getenv('AUTH_ALLOW_HOSTS'),
    ],

    'refreshToken' => [
        'ttl' => 3600 * 24 * 7,
    ],
];
