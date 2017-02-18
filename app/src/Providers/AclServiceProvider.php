<?php

namespace App\Providers;

use App\Common\Acl;
use Pimple\Container;

final class AclServiceProvider extends BaseServiceProvider
{
    /**
     * Register ACL service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config = $container['settings']['acl'];

        $container['acl'] = function() use ($config) {
            $acl = new Acl($config);

            return $acl;
        };
    }
}
