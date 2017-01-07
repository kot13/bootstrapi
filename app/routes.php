<?php
// Routes
$app->group('/api', function () {
    $this->options('[/{params:.*}]', function ($request, $response) {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, PUT, PATCH, POST, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withStatus(200);
    });

    $this->post('/token', 'App\Controller\TokenController:auth');

    $this->group('/{entity:user}', function () {
        $this->get('', 'App\Controller\UserController:actionIndex');
        $this->post('', 'App\Controller\UserController:actionCreate');
        $this->get('/{id:[0-9]+}', 'App\Controller\UserController:actionGet');
        $this->patch('/{id:[0-9]+}', 'App\Controller\UserController:actionUpdate');
        $this->delete('/{id:[0-9]+}', 'App\Controller\UserController:actionDelete');
        $this->post('/change-password', 'App\Controller\UserController:actionChangePassword');
        $this->post('/request-password-reset', 'App\Controller\UserController:actionRequestResetPassword');
        $this->post('/reset-password', 'App\Controller\UserController:actionResetPassword');
    });

    $this->group('/{entity:role|right}', function () {
        $this->get('', 'App\Controller\CrudController:actionIndex');
        $this->get('/{id:[0-9]+}', 'App\Controller\CrudController:actionGet');
        $this->post('', 'App\Controller\CrudController:actionCreate');
        $this->patch('/{id:[0-9]+}', 'App\Controller\CrudController:actionUpdate');
        $this->delete('/{id:[0-9]+}', 'App\Controller\CrudController:actionDelete');
    });

    $this->group('/{entity:log}', function () {
        $this->get('', 'App\Controller\CrudController:actionIndex');
        $this->get('/{id:[0-9]+}', 'App\Controller\CrudController:actionGet');
    });
});
