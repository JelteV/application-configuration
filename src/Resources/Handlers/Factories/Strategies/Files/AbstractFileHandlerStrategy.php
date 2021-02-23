<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;
use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattenerInterface;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyInterface;
use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 *  Implementation of the functionalities that all handler strategy will that process FileConfigurationResource
 *  instances will use.
 */
abstract class AbstractFileHandlerStrategy implements HandlerStrategyInterface
{
    /**
     * The entries flattener to process the application settings data from the ConfigurationResource instance.
     *
     * @var ConfigurationEntriesFlattenerInterface $entriesFlattener
     */
    private ConfigurationEntriesFlattenerInterface $entriesFlattener;

    /**
     * Initialize a new Handler Strategy instance.
     *
     * @param ConfigurationEntriesFlattenerInterface $entriesFlattener The entries flattener to process the application settings data.
     */
    public function __construct(ConfigurationEntriesFlattenerInterface $entriesFlattener) {
        $this->entriesFlattener = $entriesFlattener;
    }

    /**
     * Read and retrieve the entries from the given configuration resource.
     *
     * @param ConfigurationResourceInterface $configurationResource The configuration resource to get the entries for.
     * @return null|ResourceEntry[] Returns the list of valid entries on success.
     */
    public function getEntries(ConfigurationResourceInterface $configurationResource): ? array
    {
        $entries = null;

        if ($content = $this->getResourceContent($configurationResource)) {
            $entries = $this->entriesFlattener->execute($content);
        }

        return $entries;
    }

    /**
     * Get the file extensions that handler strategy can handle.
     *
     * @return string[] The list of supported file extensions
     */
    abstract public static function getExtensions(): array;

    /**
     * Read the content of the given resource.
     *
     * @param ConfigurationResourceInterface $configurationResource The ConfigurationResource to read the content for.
     * @return null|array On succes returns the content of the specified resource.
     */
    abstract protected function getResourceContent(ConfigurationResourceInterface $configurationResource): ? array;
}
