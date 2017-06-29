<?php

namespace App\Common\Config;

use Exception;

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

            // Import all config sections
            $settings = $loader->importAll();

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
