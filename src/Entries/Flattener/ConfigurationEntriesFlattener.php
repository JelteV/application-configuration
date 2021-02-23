<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

use JelteV\ApplicationConfiguration\Entries\Entry\ResourceEntry;

/**
 * The entry flattener for resources that support application settings consisting of multiple values.
 *
 * This flattener is able to process application settings data that has a nested structure.
 */
class ConfigurationEntriesFlattener extends AbstractConfigurationEntriesFlattener
{
    /**
     * The entries that have been processed and flattened.
     *
     * @var ResourceEntry[] $flattenEntries
     */
    private array $flattenEntries   = [];

    /**
     * Stores the path chain element(s) of the found application setting.
     *
     * @var string[] $pathChain
     */
    private array $pathChain     = [];

    private array $dataElementTypeList    = [];

    /**
     * Convert the application settings data into ResourceEntry instance.
     *
     * @param array $content The application settings data to flatten.
     * @return null|ResourceEntry[] On success one or more ResourceEntry instances.
     */
    protected function getResourceEntries(array $content): ?array
    {
        foreach ($content as $key => $entryValue) {
            $this->addPathChainElement($key);

            if ($this->isEntryValue($entryValue)) {
                $this->addFlattenResourceEntry($this->createResourceEntry($this->pathChain, $entryValue, false));
            } else {
                $this->explore($entryValue);
            }

            $this->removeCurrentPathChainElement();
        }

        $entries = $this->flattenEntries;

        $this->reset();

        return $entries;
    }

    /**
     * Explore the application settings data to find the application settings.
     *
     * @param mixed $data The application settings data to explore.
     * @param null|bool $isCollectionEntry When set to true application setting that is currently processed could
     * consist of one or more values.
     */
    private function explore($data, bool $isCollectionEntry = null)
    {
        $this->registerDataElementType($data);

        $entries         = (array) $data;
        $keys            = array_keys($entries);

        foreach($keys as $key) {
            $entryValue             = $entries[$key];
            $isPathChainElement     = $this->isPathChainElement($key);
            $isEntryValue           = $this->isEntryValue($entryValue);

            if ($isPathChainElement) {
                $isCollectionEntry = false;
                $this->addPathChainElement($key);
            } else {
                // @todo: Double check if this is is correct.
                $isCollectionEntry = true;
            }

            // Have we discovered application setting value?
            if (!$isEntryValue) {
                // Look deeper into the application settings data.
                $this->explore($entryValue, $isCollectionEntry);
            } else {
                // A application settings value is detected!
                $this->addFlattenResourceEntry($this->createResourceEntry($this->pathChain, $entryValue, $isCollectionEntry));
            }

            if ($isPathChainElement) {
                $this->removeCurrentPathChainElement();
                $this->removeCurrentEntryType();
            }
        }
    }

    /**
     * Add the new element to the path chain that is discovered.
     *
     * @param string $element The path chain element to add.
     */
    private function addPathChainElement(string $element)
    {
        $this->pathChain[] = $element;
    }

    /**
     * Remove (LIFO) the last discovered path chain element.
     */
    private function removeCurrentPathChainElement()
    {
        if (!empty($this->pathChain)) {
            array_pop($this->pathChain);
        }
    }

    /**
     * Add the resource entry to the list of flattened entries.
     *
     * @param ResourceEntry $resourceEntry The ResourceEntry to add to the list.
     */
    private function addFlattenResourceEntry(ResourceEntry $resourceEntry)
    {
        $this->flattenEntries[] = $resourceEntry;
    }

    /**
     * Keep track of the data element types we already discovered.
     * This list allows us the know if the previous element in the application data was a collection entry or not.
     * This is useful when traveling through the application data structure.
     *
     * @param mixed $element The element to determine the data type for.
     */
    private function registerDataElementType($element)
    {
        // check if we are processing array element or object properties.
        $this->dataElementTypeList[] = is_object($element);
    }

    /**
     * Get the data type of the most recent discovered data element.
     *
     * @return bool Returns true if the data element is an object.
     */
    private function getCurrentDataElementType(): bool
    {
        return end($this->dataElementTypeList);
    }

    /**
     * Remove the data type from the tracking list for the most recent discovered data element.
     */
    private function removeCurrentEntryType()
    {
        if (!empty($this->dataElementTypeList)) {
            array_pop($this->dataElementTypeList);
        }
    }

    /**
     * Checking the key to prevent unwanted key value are added to the path.
     *
     * Application setting values of an application setting that could consists of one or more values are represented by
     * an numeric indexed array. I don't want this index value to be added to path chain. So a numerical index is not
     * considered path chain element.
     *
     * @param mixed $key The key to check
     * @return bool Returns true if the key is an valid path chain element.
     */
    private function isPathChainElement($key) : bool
    {
        return !is_int($key) || (is_int($key) && $this->getCurrentDataElementType());
    }

    /**
     * Check if the given value is an entry value.
     *
     * @param mixed $value The value to check.
     * @return bool Returns True if the value is an entry value.
     */
    private function isEntryValue($value): bool
    {
        return is_scalar($value);
    }

    /**
     * Reset entry flattener by cleaning up the tracking registers.
     */
    private function reset()
    {
        $this->flattenEntries       = [];
        $this->dataElementTypeList  = [];
        $this->pathChain            = [];
    }
}
