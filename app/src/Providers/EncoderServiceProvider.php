<?php

namespace App\Providers;

use App\Common\JsonApiEncoder;
use Pimple\Container;

final class EncoderServiceProvider extends BaseServiceProvider
{
    /**
     * Register encoder service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config  = $container['settings'];

        $container['encoder'] = function() use ($config) {
            $encoder = new JsonApiEncoder($config);

            return $encoder;
        };
    }
}
