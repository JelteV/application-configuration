<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;

/**
 * Ensure that all the entry flattener instances implement these functionalities.
 */
interface ConfigurationEntriesFlattenerInterface
{
    /**
     * Start the process of converting application settings data from its resource specific data-structure to a
     * ResourceEntry instance(s).
     *
     * @param array $content The application settings data to flatten.
     * @return null|ResourceEntry[] On success one or more ResourceEntry instances.
     */
    public function execute(array $content): ? array;
}
