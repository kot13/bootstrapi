<?php

namespace App\Providers;

use Pimple\Container;
use App\Common\Renderer;
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

        $container['renderer'] = function() use ($config) {
            $renderer = new Renderer($config);

            return $renderer;
        };

        $container['mailRenderer'] = function() use ($config) {
            $renderer = new MailRenderer($config['mailTemplate']);

            return $renderer;
        };
    }
}
