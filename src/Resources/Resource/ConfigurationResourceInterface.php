<?php

namespace JelteV\ApplicationConfiguration\Resources\Resource;

/**
 * Ensure all configuration resources implement these functionalities.
 */
interface ConfigurationResourceInterface
{
    /**
     * Get the resource.
     *
     * @return mixed The resource.
     */
    public function getResource();
}
