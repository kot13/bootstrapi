<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => (defined('DEBUG_MODE') && DEBUG_MODE == 1),

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

        'mailTemplate' => __DIR__ . '/../mail',

            // DB
        'database' => require(__DIR__ . '/../config/db.php'),

        // ACL
        'acl' =>
            [
                'default_role' => 'guest',
                'roles' => [
                    'guest' => [],
                    'user'  => ['guest'],
                    'admin' => ['user'],
                ],
                /*
                 * just a list of generic resources for manual checking
                 * specified here so can be used in the code if needs be
                 */
                'resources' => [
//                'user' => null,
                ],
                // where we specify the guarding!
                'guards' => [
                    /*
                     * list of resource to roles to permissions
                     * optional
                     * if included all resources default to deny unless specified.
                     */
                    'resources' => [
//                    ['user', ['admin']],
                    ],
                    /*
                     * list of literal routes for guarding.
                     * optional
                     * if included all routes default to deny unless specified.
                     * Similar format to resource 'resource' route, roles, 'permission' action
                     * ['route', ['roles'], ['methods',' methods1']]
                     */
                    'routes' => [
                        ['/api/token',  ['guest'],  ['post']],
                        ['/api/user',   ['user'],   ['get']],
                    ],
                    /*
                     * list of callables to resolve against
                     * optional
                     * if included all callables default to deny unless specified.
                     * 'permission' section is combined into the callable section
                     * ['callable', ['roles']]
                     */
                    'callables' => [
                        ['App\Controller\CrudController',               ['user']], // $app->map(['GET'], '/user',      'App\Controller\UserController'); class with __invoke
                        ['App\Controller\CrudController:actionIndex',   ['user']], // $app->map(['GET'], '/user/{id}', 'App\Controller\UserController:getAction'); class and method
                        ['App\Controller\CrudController:actionGet',     ['user']],
                        ['App\Controller\CrudController:actionCreate',  ['user']],
                        ['App\Controller\CrudController:actionUpdate',  ['user']],
                        ['App\Controller\CrudController:actionDelete',  ['user']],

                        ['App\Controller\UserController:actionIndex',  ['admin']],
                        ['App\Controller\UserController:actionGet',    ['admin']],
                        ['App\Controller\UserController:actionCreate', ['admin']],
                        ['App\Controller\UserController:actionUpdate', ['admin']],
                        ['App\Controller\UserController:actionDelete',  ['user']],
                    ]
                ]
            ],

        'translate' => [
            'path' => __DIR__ . '/../lang',
            'locale' => 'ru',
        ],

        'params' => require(__DIR__ . '/../config/params.php'),
    ],
];
