<?php

return [
    'encoder' => [
        'schemas' => [
            'default' => [
                'App\Model\Log'       => 'App\Schema\LogSchema',
                'App\Model\Right'     => 'App\Schema\RightSchema',
                'App\Model\Role'      => 'App\Schema\RoleSchema',
                'App\Model\User'      => 'App\Schema\UserSchema',
                'App\Model\MediaFile' => 'App\Schema\MediaFileSchema',
            ],
            'extended' => [
                'App\Model\Log'       => 'App\Schema\LogSchema',
                'App\Model\Right'     => 'App\Schema\RightSchema',
                'App\Model\Role'      => 'App\Schema\RoleSchema',
                'App\Model\User'      => 'App\Schema\UserSchemaExtended',
                'App\Model\MediaFile' => 'App\Schema\MediaFileSchema',
            ],
        ],
    ],
];
