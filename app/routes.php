<?php
// Routes
$app->group('/api', function () {
    $this->options('[/{params:.*}]', function ($request, $response, $args) {
        $allowHost = $this->settings['params']['allowHost'];

        return $response->withHeader('Access-Control-Allow-Origin', $allowHost)
            ->withHeader('Access-Control-Allow-Methods', 'GET, PUT, PATCH, POST, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withStatus(200);
    });

    $this->post('/token', 'App\Controller\TokenController:auth');

    $this->group('/{entity:user}', function () {
        $this->get('', 'App\Controller\ApiController:actionIndex');
        $this->get('/{id:[0-9]+}', 'App\Controller\ApiController:actionGet');
        $this->post('', 'App\Controller\ApiController:actionCreate');
        $this->patch('/{id:[0-9]+}', 'App\Controller\ApiController:actionUpdate');
        $this->delete('/{id:[0-9]+}', 'App\Controller\ApiController:actionDelete');
    });

});