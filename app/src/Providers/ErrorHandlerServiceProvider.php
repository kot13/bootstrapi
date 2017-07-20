<?php

namespace App\Providers;

use App\Common\JsonException;
use Pimple\Container;
use Exception;

final class ErrorHandlerServiceProvider extends BaseServiceProvider
{
    /**
     * Register error handlers service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['errorHandler'] = function(Container $container) {
            return function($request, $response, Exception $exception) use ($container) {
                $details = $container['settings']['displayErrorDetails'] ? $exception->getMessage() : 'Internal server error';
                $error   = new JsonException(null, 500, 'Internal server error', $details);

                return $container['apiRenderer']->jsonResponse($response, $error->statusCode, $error->encodeError());
            };
        };

        $container['notAllowedHandler'] = function() {
            return function($request, $response, $methods) {
                throw new JsonException(null, 405, 'Method Not Allowed', 'Method must be one of: '.implode(', ', $methods));
            };
        };

        $container['notFoundHandler'] = function() {
            return function() {
                throw new JsonException(null, 404, 'Not found', 'Entity not found');
            };
        };

    }
}
