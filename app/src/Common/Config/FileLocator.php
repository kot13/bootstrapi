<?php

namespace App\Common\Config;

/**
 * FileLocator uses an array of pre-defined paths to find files.
 */
class FileLocator extends \Symfony\Component\Config\FileLocator
{
    /**
     * Returns an array of full paths to all locatable files
     *
     * @return array
     */
    public function locateAll()
    {
        $res = [];

        // no paths for files specified - no files to locate
        if (!is_array($this->paths)) {
            return $res;
        }

        // traverse over all paths specified for search in locator
        foreach ($this->paths as $path) {
            // fetch entries in each path
            $dir_entries = scandir($path);
            foreach ($dir_entries as $dir_entry) {
                // include paths to files into result set
                $filename = $path.DIRECTORY_SEPARATOR.$dir_entry;
                if (@is_file($filename) && @is_readable($filename)) {
                    $res[] = $filename;
                }
            }
        }

        // here $res is an array of paths to files
        // make files in res set unique
        $res = array_unique($res);

        // return array of file paths
        return $res;
    }
}
