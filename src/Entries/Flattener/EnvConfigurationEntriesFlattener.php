<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;
use JelteV\ApplicationConfiguration\Entries\Filters\EnvEntryCommentFilter;

/**
 * The entry flattener for .env resources that doesn't support application settings consisting of multiple values.
 *
 * This flattener is able to process application settings data that has a nested structure.
 */
class EnvConfigurationEntriesFlattener extends AbstractConfigurationEntriesFlattener
{
    /**
     * Convert the application settings data into ResourceEntry instance.
     *
     * @param array $content The application settings data to flatten.
     * @return null|ResourceEntry[] On success one or more ResourceEntry instances.
     */
    protected function getResourceEntries(array $content): ?array
    {
        $entries = null;

        foreach ($content as $line) {
            if ($filteredLine = EnvEntryCommentFilter::parseLine($line)) {
                if ($entry = $this->parseEntry($filteredLine)) {
                    $entryPath = $entry[0];
                    $entryValue = $entry[1];

                    $entries[] = $this->createResourceEntry($entryPath, $entryValue, false);
                }
            }
        }

        return $entries;
    }

    /**
     * Try to parse the given line into an entry.
     *
     * @param string $line The line to parse.
     * @return null|array On success return an array containing the application setting path chain
     * and the application setting value.
     */
    private function parseEntry(string $line): ?array
    {
        $parts = explode('=', $line);
        $entry = null;

        if (count($parts) === 2) {
            $path = explode('.', $parts[0]);
            $value = $parts[1];

            $entry = [$path, $value];
        }

        return $entry;
    }
}
