<?php

namespace App\Console\Traits;

trait CodeGenerate
{
    /**
     * Generate code
     *
     * @param array  $placeHolders
     * @param array  $replacements
     * @param string $templateName
     * @param string $resultPath
     * @return bool|int
     */
    public function generateCode($placeHolders, $replacements, $templateName, $resultPath)
    {
        $templatePath = CODE_TEMPLATE_PATH.'/'.$templateName;
        if (false === file_exists($templatePath)) {
            throw new \RunTimeException(sprintf('Not found template %s', $templatePath));
        }

        $template = file_get_contents($templatePath);

        $code = str_replace($placeHolders, $replacements, $template);

        return file_put_contents($resultPath, $code);
    }

    /**
     * @param string $baseName
     * @param string $dir
     * @return string
     * @throws \Exception
     */
    private function getPath($baseName, $dir)
    {
        $dir = rtrim($dir, '/');
        if (!file_exists($dir)) {
            throw new \Exception(sprintf('Commands directory "%s" does not exist.', $dir));
        }

        return $dir.'/'.$baseName;
    }
}
