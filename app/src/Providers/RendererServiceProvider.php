<?php

namespace App\Providers;

use Pimple\Container;
use App\Common\ApiRenderer;
use App\Common\MailRenderer;

final class RendererServiceProvider extends BaseServiceProvider
{
    /**
     * Register renderer service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config = $container['settings'];

        $container['apiRenderer'] = function() use ($config) {
            $renderer = new ApiRenderer($config);

            return $renderer;
        };

        $container['mailRenderer'] = function() use ($config) {
            $renderer = new MailRenderer($config['mailTemplate']);

            return $renderer;
        };
    }
}
