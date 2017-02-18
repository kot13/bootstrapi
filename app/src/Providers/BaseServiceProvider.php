<?php

namespace App\Providers;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

abstract class BaseServiceProvider implements ServiceProviderInterface
{
    /**
     * Register service
     *
     * @param Container $container
     *
     * @return void
     */
    abstract public function register(Container $container);
}
