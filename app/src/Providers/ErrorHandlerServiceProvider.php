<?php

namespace App\Providers;

use App\Common\JsonException;
use Pimple\Container;

final class ErrorHandlerServiceProvider extends BaseServiceProvider
{
    /**
     * Register error handlers service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['errorHandler'] = function (Container $c) {
            return function($request, $response, $exception) use ($c) {
                $details = (defined('DEBUG_MODE') && DEBUG_MODE == 1) ? $exception->getMessage() : 'Internal server error';
                $e       = new JsonException(null, 500, 'Internal server error', $details);

                return $c->get('renderer')->jsonApiRender($response, $e->statusCode, $e->encodeError());
            };
        };

        $container['notAllowedHandler'] = function (Container $c) {
            return function($request, $response, $methods) use ($c) {
                throw new JsonException(null, 405, 'Method Not Allowed', 'Method must be one of: '.implode(', ', $methods));
            };
        };

        $container['notFoundHandler'] = function (Container $c) {
            return function() use ($c) {
                throw new JsonException(null, 404, 'Not found', 'Entity not found');
            };
        };

    }
}
