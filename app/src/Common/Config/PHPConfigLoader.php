<?php

namespace App\Common\Config;

use Symfony\Component\Config\Loader\FileLoader;

/**
 * Class PHPConfigLoader
 *
 * @package App\Common\Config
 */
class PHPConfigLoader extends FileLoader
{
    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        return require_once $resource;
    }

    /**
     * Returns whether current loader supports specified resource load
     *
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        // $resource is expected to be path to config file
        if (!is_string($resource)) {
            return false;
        }

        $extension = pathinfo($resource, PATHINFO_EXTENSION);

        // PHP loader accepts PHP files
        return  $extension == 'php';
    }
}