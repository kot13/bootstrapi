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

    'observers' => [
        \App\Observers\CreatedByAndUpdatedByObserver::class => [
            \App\Model\Right::class,
            \App\Model\Role::class,
            \App\Model\User::class,
            \App\Model\MediaFile::class,
        ],

        \App\Observers\LoggerObserver::class => [
            \App\Model\Right::class,
            \App\Model\Role::class,
            \App\Model\User::class,
            \App\Model\MediaFile::class,
        ],
    ],
];
