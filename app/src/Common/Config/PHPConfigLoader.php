<?php

namespace App\Common\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class PHPConfigLoader
 *
 * @package App\Common\Config
 */
class PHPConfigLoader extends FileLoader
{
    /**
     * Import all loadable resources
     *
     * @return array
     * @throws FileLoaderLoadException
     */
    public function importAll()
    {
        // in order to import all we need to have locator to locate these "all"
        // locator need
        if (empty($this->locator)) {
            throw new FileLoaderLoadException("importAll()");
        }

        // locator must be capable of locating all
        if (!is_callable([$this->locator, 'locateAll'])) {
            throw new FileLoaderLoadException("locateAll()");
        }

        // build list of 'all' files
        $files = $this->locator->locateAll();

        // and now try to import configuration from this list of 'all' files
        $res = [];
        foreach ($files as $file) {
            if ($this->supports($file)) {
                // file is supported - load/import it
                $config = $this->load($file);

                if (!empty($config['definition'])) {
                    // loaded config can be verified
                    $processor = new Processor();
                    $configDefinition = new $config['definition']();
                    // process config according to specified definition
//file_put_contents('/tmp/log', print_r($config, true), FILE_APPEND);
                    $config = $processor->processConfiguration(
                        $configDefinition,
                        [$config]
                    );
//file_put_contents('/tmp/log', print_r($config, true), FILE_APPEND);
                }

                // combine all configs into one "unified general config"
                $res = array_merge($res, $config);
            }
        }

        // array of configuration
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        // for PHP file load process is quite simple - just return config file content
        return require_once $resource;
    }

    /**
     * Returns whether current loader supports specified resource load
     *
     * @param mixed $resource path to config file
     * @param null $type unsused
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        // $resource is expected to be path to config file
        if (!is_string($resource)) {
            return false;
        }

        // specified config file should be readable
        if (!@is_file($resource) || !@is_readable($resource)) {
            return false;
        }

        // simple check - PHP loader accepts PHP files, thus let's check file extension to be 'php'

        // fetch config file extension
        $extension = pathinfo($resource, PATHINFO_EXTENSION);

        // PHP loader accepts PHP files
        return  $extension == 'php';
    }
}
