<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattenerInterface;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyInterface;

abstract class AbstractFileHandlerStrategy implements HandlerStrategyInterface
{
    private \SplFileInfo $file;

    /**
     * @var mixed $content
     */
    private $content;

    private $entries;

    /**
     * @var ConfigurationEntriesFlattenerInterface $entriesFlattener
     */
    private $entriesFlattener;

    public function __construct(\SplFileInfo $file, ConfigurationEntriesFlattenerInterface $entriesFlattener)
    {
        $this->file = $file;
        $this->entriesFlattener = $entriesFlattener;
    }

    public function getEntries(): ? array
    {
        if (!isset($this->content)) {
            if ($content = $this->getResourceContent()) {
                $this->content = $content;
                $this->entries = $this->entriesFlattener->execute($this->content);
            }
        }

        $test = $this->entries;

        return $this->entries;
    }

    public function getResource(): \SplFileInfo
    {
        return $this->file;
    }

    abstract public  static function getExtensions(): array;

    abstract protected function getResourceContent(): ? array;
}
