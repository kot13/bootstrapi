<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Pimple\Container;

final class DatabaseServiceProvider extends BaseServiceProvider
{
    /**
     * Register database service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config  = $container['settings'];
        $capsule = new Capsule();
        foreach ($config['database']['connections'] as $name => $connection) {
            $capsule->addConnection($connection, $name);
        }
        $capsule->setEventDispatcher(new Dispatcher(new IlluminateContainer()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $container['databaseManager'] = $capsule->getDatabaseManager();
    }
}
