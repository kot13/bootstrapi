<?php

namespace App\Providers;

use Pimple\Container;

final class MailerServiceProvider extends BaseServiceProvider
{
    /**
     * Register mailer service provider.
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $config = $container['settings'];

        $container['mailer'] = function () use ($config) {
            $transport = \Swift_MailTransport::newInstance();
            $mailer    = \Swift_Mailer::newInstance($transport);

            return $mailer;
        };
    }
}
