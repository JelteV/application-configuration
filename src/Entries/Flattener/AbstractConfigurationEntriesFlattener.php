<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;

/**
 * Represents the general features every entry flattener instance uses or should implement.
 * An entry flattener is responsible for converting application settings data from its resource specific data-structure
 * to a ResourceEntry instance(s).
 */
abstract class AbstractConfigurationEntriesFlattener implements ConfigurationEntriesFlattenerInterface
{
    /**
     * Whether or not the resource for which the application settings are being processed here supports
     * application settings consisting of multiple values (like an array).
     *
     * @var bool $supportsCollectionEntries
     */
    private bool $supportsCollectionEntries;

    /**
     * Initialize a new Configuration entry flattener.
     *
     * @param bool $supportsCollectionEntries Indicator whether or not application settings consisting of multiple values is supported.
     */
    public function __construct(bool $supportsCollectionEntries)
    {
        $this->supportsCollectionEntries = $supportsCollectionEntries;
    }

    /**
     * Start the process of converting application settings data from its resource specific data-structure to a
     * ResourceEntry instance(s).
     *
     * @param array $content The application settings data to flatten.
     * @return null|ResourceEntry[] On success one or more ResourceEntry instances.
     */
    public function execute(array $content): ? array
    {
        return $this->getResourceEntries($content);
    }

    /**
     * Convert the application settings data into ResourceEntry instance.
     *
     * @param array $content The application settings data to flatten.
     * @return null|ResourceEntry[] On success one or more ResourceEntry instances.
     */
    abstract protected function getResourceEntries(array $content): ?array;

    /**
     * Create a new ResourceEntry instance representing a application setting value.
     *
     * @param string[] $path The chain of elements identifying the specific application setting
     * @param mixed $value The value of the application setting.
     * @param bool $isCollectionEntry Indicator if the application setting consists of a collection of values (like an array).
     * @return ResourceEntry a new ResourceEntry instance.
     */
    protected function createResourceEntry(array $path, $value, bool $isCollectionEntry): ResourceEntry
    {
        return new ResourceEntry($path, $value, $this->supportsCollectionEntries && $isCollectionEntry);
    }
}
