<?php

namespace JelteV\ApplicationConfiguration\Entries\Flattener;

use JelteV\ApplicationConfiguration\Entries\Filters\EnvEntryCommentFilter;

class EnvConfigurationEntriesFlattener implements ConfigurationEntriesFlattenerInterface
{
    private $commentFilter;

    public function __construct()
    {
        $this->commentFilter = null;
    }

    public function execute(array $content)
    {
        $entries = null;

        foreach ($content as $line) {
            if ($entry = EnvEntryCommentFilter::parseLine($line)) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }
}
