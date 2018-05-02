<?php

namespace App\Common\Config;

use Dotenv\Dotenv;
use Exception;

class Settings
{
    /**
     * @return array - array of settings
     */
    public static function build()
    {
        $dotenv = new Dotenv(ROOT_PATH);
        $dotenv->load();

        // Load settings from files
        try {
            // Directories list where config files located
            $directories = [
                CONFIG_PATH,
            ];

            // Locator to find files
            $locator = new FileLocator($directories);

            // Load config file into an array
            $loader = new PHPConfigLoader($locator);

            // Import all config sections
            $settings = $loader->importAll();

        } catch (Exception $e) {
            die($e->getMessage());
        }

        // Adjust error reporting
        if (stripos($settings['params']['env'], 'dev') !== false) {
            $settings['displayErrorDetails'] = true;
            error_reporting(E_ALL);
        }

        return [
            'settings' => $settings,
        ];
    }
}
