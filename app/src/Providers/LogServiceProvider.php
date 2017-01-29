<?php

namespace App\Providers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Pimple\Container;

final class LogServiceProvider extends BaseServiceProvider
{
    /**
     * Register log service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config = $container['settings']['logger'];

        $container['logger'] = function (Container $c) use ($config) {
            $logger = new Logger($config['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler($config['path'], $config['level']));

            return $logger;
        };
    }
}
