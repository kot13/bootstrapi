<?php

namespace App\Providers;

use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Pimple\Container;

final class ValidationServiceProvider extends BaseServiceProvider
{
    /**
     * Register validation service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config = $container['settings'];

        // translation
        $container['translator'] = function() use ($config) {
            $translateFileLoader = new FileLoader(new Filesystem(), $config['translate']['path']);
            $translator          = new Translator($translateFileLoader, $config['translate']['locale']);

            return $translator;
        };

        // validation
        $container['validation'] = function(Container $container) use ($config) {
            $validation = new Factory($container['translator']);
            $presence   = new DatabasePresenceVerifier($container['databaseManager']);
            $validation->setPresenceVerifier($presence);

            return $validation;
        };
    }
}
