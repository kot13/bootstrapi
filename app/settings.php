<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => (defined('DEBUG_MODE') && DEBUG_MODE == 1),

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        'swagger' => [
            'baseDir' => __DIR__ . '/src',
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

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
                        ['App\Controller\ApiController',               ['user']], // $app->map(['GET'], '/user',      'App\Controller\UserController'); class with __invoke
                        ['App\Controller\ApiController:actionIndex',   ['user']], // $app->map(['GET'], '/user/{id}', 'App\Controller\UserController:getAction'); class and method
                        ['App\Controller\ApiController:actionGet',     ['user']],
                        ['App\Controller\ApiController:actionCreate',  ['user']],
                        ['App\Controller\ApiController:actionUpdate',  ['user']],
                        ['App\Controller\ApiController:actionDelete',  ['user']],

                        ['App\Controller\UserController:actionCreate', ['admin']],
                        ['App\Controller\UserController:actionUpdate', ['user']],
                    ]
                ]
            ],

        'translate' => [
            'path' => __DIR__ . '/../lang',
            'locale' => 'en',
        ],

        'params' => require(__DIR__ . '/../config/params.php'),
    ],
];
