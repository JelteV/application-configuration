<?php

namespace JelteV\ApplicationConfiguration\Resources\Resource\Files;

use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 * Represent an file configuration resource.
 */
class FileConfigurationResource implements ConfigurationResourceInterface
{
    /**
     * The file resource.
     *
     * @var \SplFileInfo $resource
     */
    private \SplFileInfo $resource;

    /**
     * Initialize new FileConfigurationResource instance.
     *
     * @param \SplFileInfo $resource The file resource.
     */
    public function __construct(\SplFileInfo $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the resource.
     *
     * @return \SplFileInfo The resource.
     */
    public function getResource(): \SplFileInfo
    {
        return $this->resource;
    }
}
