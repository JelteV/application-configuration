<?php

namespace JelteV\ApplicationConfiguration;

use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;

class Module
{
    public function __construct()
    {
        $this->initialize();
    }

    private function initialize()
    {
        // @todo: make this more flexible.
        $rootDirectory = php_sapi_name() === 'cli'
            ? '/home/jelte/Documents/projects/application-configuration'
            : $_SERVER['DOCUMENT_ROOT'];

        $this->setProjectRootDirectory($rootDirectory);
    }

    private function setProjectRootDirectory($directory)
    {
        if (!ResourceHelper::isDirectoryPath($directory, false)) {
            throw new \RuntimeException("Invalid value supplied as working directory path, got: {$directory}");
        }

        if (!chdir($directory)) {
            throw new \RuntimeException("Could not change working directory to {$directory}");
        }
    }
}
