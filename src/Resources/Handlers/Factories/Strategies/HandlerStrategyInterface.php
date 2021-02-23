<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;
use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 * Ensure that all the handler strategy instances implement these functionalities.
 */
interface HandlerStrategyInterface
{
    /**
     * Read and retrieve the entries from the given configuration resource.
     *
     * @param ConfigurationResourceInterface $configurationResource The configuration resource to get the entries for.
     * @return null|ResourceEntry[] Returns the list of valid entries on success.
     */
    public function getEntries(ConfigurationResourceInterface $configurationResource): ? array;
}
