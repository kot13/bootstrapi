<?php

namespace App\Common\Config;

use Dotenv\Dotenv;

class Settings
{
    /**
     * @var array
     */
    private static $requiredVariable = [
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
        'APP_HOST',
        'APP_API_HOST',
        'APP_ENV',
        'AUTH_SECRET_KEY',
        'AUTH_ALLOW_HOSTS'
    ];

    /**
     * @return array - array of settings
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    public static function build()
    {
        $dotenv = new Dotenv(ROOT_PATH);
        $dotenv->load();
        $dotenv->required(self::$requiredVariable);

        // Locator to find files
        $locator = new FileLocator([
            CONFIG_PATH,
        ]);

        // Load config file into an array
        $loader = new PHPConfigLoader($locator);

        // Import all config sections
        $settings = $loader->importAll();

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
