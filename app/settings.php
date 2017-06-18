<?php

use App\Common\Acl;

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => (defined('DEBUG_MODE') && DEBUG_MODE == 1),

        // monolog settings
        'logger' => [
            'name'  => 'app',
            'path'  => __DIR__.'/../log/app.log',
            'level' => Monolog\Logger::DEBUG,
        ],

        'mailTemplate' => __DIR__.'/../mail',

        // DB
        'database' => @file_exists(__DIR__.'/../config/db.php') ? require(__DIR__.'/../config/db.php') : die("ERROR - can't find file " . __DIR__.'/../config/db.php' . PHP_EOL),

        // ACL
        'acl' => [
            'default_role' => 'guest',

            'roles' => [
                // role => [parents]
                'guest' => [],
                'user'  => ['guest'],
                'admin' => ['user'],
            ],

            /**
             * just a list of generic resources for manual checking
             * specified here so can be used in the code if needs be
             * Example: ['user' => null]
             */
            'resources' => [
                // resource => parent
            ],

            // where we specify the guarding!
            'guards' => [

                /**
                 * list of resource to roles to permissions
                 * optional
                 * if included all resources default to deny unless specified.
                 * Example: ['user', ['admin']]
                 */
                Acl::GUARD_TYPE_RESOURCE => [

                ],

                /**
                 * list of literal routes for guarding.
                 * optional
                 * if included all routes default to deny unless specified.
                 * Similar format to resource 'resource' route, roles, 'permission' action
                 * ['route', ['roles'], ['methods',' methods1']]
                 */
                Acl::GUARD_TYPE_ROUTE => [
                    // resource, roles, privileges
                    ['/api/token', ['guest'], [Acl::PRIVILEGE_POST]],
                    ['/api/user',  ['user'],  [Acl::PRIVILEGE_GET]],
                ],

                /**
                 * list of callables to resolve against
                 * optional
                 * if included all callables default to deny unless specified.
                 * 'permission' section is combined into the callable section
                 * ['callable', ['roles']]
                 */
                Acl::GUARD_TYPE_CALLABLE => [
                    // resource, roles, privileges
                    ['App\Controller\CrudController',              ['user']],
                    ['App\Controller\CrudController:actionIndex',  ['user']],
                    ['App\Controller\CrudController:actionGet',    ['user']],
                    ['App\Controller\CrudController:actionCreate', ['user']],
                    ['App\Controller\CrudController:actionUpdate', ['user']],
                    ['App\Controller\CrudController:actionDelete', ['user']],

                    ['App\Controller\UserController:actionIndex',  ['user']],
                    ['App\Controller\UserController:actionGet',    ['user']],
                    ['App\Controller\UserController:actionCreate', ['admin']],
                    ['App\Controller\UserController:actionUpdate', ['admin']],
                    ['App\Controller\UserController:actionDelete', ['admin']],
                ]
            ]
        ],

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

        'translate' => [
            'path' => __DIR__.'/../lang',
            'locale' => 'ru',
        ],

        'params' => @file_exists(__DIR__.'/../config/params.php') ? require(__DIR__.'/../config/params.php') : die("ERROR - can't find file " . __DIR__.'/../config/params.php' . PHP_EOL),
    ],
];
