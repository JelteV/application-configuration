<?php

namespace JelteV\ApplicationConfiguration\Entries\Entry;

/**
 * Represents a single application setting value.
 */
class ResourceEntry
{
    /**
     * The chain of elements identifying the specific application setting. Because application settings could be nested
     * the path could contain more than one element.
     *
     * @var string[] $path
     */
    private $path;

    /**
     * The value of the application setting.
     *
     * @var mixed $value
     */
    private $value;

    /**
     * When set to true it indicates the application setting consists of a collection of values (like an array).
     * This property helps to determine if the value should be merged with existing values of the existing value
     * should be overridden.
     *
     * @var bool $isCollectionEntry
     */
    private bool $isCollectionEntry;

    /**
     * Initialize a new ResourceEntry instance.
     *
     * @param string[] $path The chain of elements identifying the specific application setting.
     * @param mixed $value The value of the application setting.
     * @param bool $isCollectionEntry Indicator if the application setting consists of a collection of values (like an array).
     */
    public function __construct(array $path, $value, bool $isCollectionEntry)
    {
        $this->path = $path;
        $this->value = $value;
        $this->isCollectionEntry = $isCollectionEntry;
    }

    /**
     * Get the chain of elements identifying the specific application setting.
     *
     * @return string[] the chain of elements identifying the specific application setting.
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * Get the value of the application setting.
     *
     * @return mixed The application setting value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get whether the application setting value is part of a collection.
     *
     * @return bool Returns True if the application setting value is part of a collection of values, otherwise False.
     */
    public function isCollectionEntry(): bool
    {
        return $this->isCollectionEntry;
    }
}
