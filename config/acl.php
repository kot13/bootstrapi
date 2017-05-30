<?php

use App\Common\Acl;

// ACL
return [
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
            ],
        ],
    ],
];