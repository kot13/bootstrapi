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
        $container['translator'] = function (Container $c) use ($config) {
            $translation_file_loader = new FileLoader(new Filesystem, $config['translate']['path']);
            $translator              = new Translator($translation_file_loader, $config['translate']['locale']);

            return $translator;
        };

        // validation
        $container['validation'] = function (Container $c) use ($config) {
            $validation = new Factory($c->get('translator'));
            $presence   = new DatabasePresenceVerifier($c->get('databaseManager'));
            $validation->setPresenceVerifier($presence);

            return $validation;
        };
    }
}