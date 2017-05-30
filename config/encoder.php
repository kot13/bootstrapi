<?php

return [
    'encoder' => [
        'schemas' => [
            'default' => [
                'App\Model\Log'   => 'App\Schema\LogSchema',
                'App\Model\Right' => 'App\Schema\RightSchema',
                'App\Model\Role'  => 'App\Schema\RoleSchema',
                'App\Model\User'  => 'App\Schema\UserSchema',
            ],
            'extended' => [
                'App\Model\Log'   => 'App\Schema\LogSchema',
                'App\Model\Right' => 'App\Schema\RightSchema',
                'App\Model\Role'  => 'App\Schema\RoleSchema',
                'App\Model\User'  => 'App\Schema\UserSchemaExtended',
            ],
        ],
    ],
];
