<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

class ConfigurationEntriesFlattener implements ConfigurationEntriesFlattenerInterface
{
    private array $flattenEntries   = [];

    private array $content     = [];

    private array $pathSections     = [];

    // @todo: come up with a more meaning full var name.
    private array $entryTypeList    = [];

    public function execute($content): array
    {
        try {
            $this->setContent($content);
        } catch (\TypeError $e) {
            $test = '';
        }

        foreach ($this->content as $key => $entryValue) {
            $this->addPathSection($key);

            if ($this->isEntryValue($entryValue)) {
                $this->addFlattenEntry($this->pathSections, $entryValue);
            } else {
                $this->flatten($entryValue);
            }

            $this->removeCurrentPathSection();
        }

        $entries = $this->flattenEntries;

        $this->reset();

        return $entries;
    }

    private function flatten($data)
    {
        $this->registerEntryType($data);

        $entries         = (array) $data;
        $keys            = array_keys($entries);

        foreach($keys as $key) {
            $entryValue             = $entries[$key];
            $isPathSection          = $this->isPathSection($key);
            $isEntryValue           = $this->isEntryValue($entryValue);

            if ($isPathSection) {
                $this->addPathSection($key);
            }

            if (!$isEntryValue) {
                $this->flatten($entryValue);
            } else {
                $this->addFlattenEntry($this->pathSections, $entryValue);
            }

            if ($isPathSection) {
                $this->removeCurrentPathSection();
                $this->removeCurrentEntryType();
            }
        }
    }

    private function addPathSection($section)
    {
        // prevent indexes of json arrays show up in the path.
        $this->pathSections[] = $section;
    }

    private function removeCurrentPathSection()
    {
        if (!empty($this->pathSections)) {
            array_pop($this->pathSections);
        }
    }

    private function addFlattenEntry(array $path, $value)
    {
        $this->flattenEntries[] = [$path, $value];
    }

    private function setContent(array $content)
    {
        $this->content = $content;
    }

    private function registerEntryType($entries)
    {
        // check if we are processing array element or object properties.
        $this->entryTypeList[] = is_object($entries);
    }

    private function getCurrentEntryType()
    {
        return end($this->entryTypeList);
    }

    private function removeCurrentEntryType()
    {
        if (!empty($this->entryTypeList)) {
            array_pop($this->entryTypeList);
        }
    }

    /**
     * Checking the key to prevent unwanted key value are added to the path.
     *
     * @param $key
     * @return bool
     */
    private function isPathSection($key) : bool
    {
        return !is_int($key) || (is_int($key) && $this->getCurrentEntryType());
    }

    private function isEntryValue($entryValue): bool
    {
        return is_scalar($entryValue);
    }

    private function reset()
    {
        $this->flattenEntries = [];
        $this->parseEntries   = [];
        $this->pathSections   = [];
    }
}
