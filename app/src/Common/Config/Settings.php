<?php

namespace App\Common\Config;

use Symfony\Component\Config\FileLocator;

class Settings
{
    /**
     * @return array of settings
     */
    public static function build()
    {
        // Build application settings

        // Load settings from files
        try {
            // Directories list where config files located
            $directories = array(
                CONFIG_PATH,
            );

            // Locator to find files
            $locator = new FileLocator($directories);

            // Load config file into an array
            $loader = new PHPConfigLoader($locator);

            // Load all config sections
            $settings = array_merge(
                $loader->load($locator->locate('acl.php')),
                $loader->load($locator->locate('db.php')),
                $loader->load($locator->locate('encoder.php')),
                $loader->load($locator->locate('logger.php')),
                $loader->load($locator->locate('mail.php')),
                $loader->load($locator->locate('params.php')),
                $loader->load($locator->locate('slim.php')),
                $loader->load($locator->locate('translate.php'))
            );
        } catch (Exception $ex) {
            die($ex->getMessage());
        }

        // Load settings from ENV vars
        $settings['params']['env'] = @getenv('APPLICATION_ENV');
        $settings['accessToken']['secret_key'] = php_sapi_name() == 'cli-server' ? 'test-key' : @getenv('SECRET_KEY');
        $settings['accessToken']['iss'] = @getenv('AUTH_ISS');

        // Adjust error reporting
        if (@stripos($settings['params']['env'],  'dev') !== false) {
            $settings['displayErrorDetails'] = true;
        }

        if ($settings['displayErrorDetails']) {
            error_reporting(E_ALL);
        }

        // Adjust settings as Slim wants to have it - inside ['settings'] section
        $settings = [
            'settings' => $settings
        ];

        // Settings are ready
        return $settings;
    }
}
