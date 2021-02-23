<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\AbstractResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\AbstractFileHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Resource\Files\FileConfigurationResource;

/**
 * Represents the handler for file resources.
 */
class FileResourceHandler extends AbstractResourceHandler
{
    /**
     * Initialize a new FileResourceHandler instance.
     *
     * @param FileConfigurationResource $configurationResource The configuration resource to handle.
     * @param AbstractFileHandlerStrategy $strategy The strategy to read the content of the FileConfigurationResource instance.
     */
    public function __construct(FileConfigurationResource $configurationResource, AbstractFileHandlerStrategy $strategy)
    {
        parent::__construct($configurationResource, $strategy);
        $this->setIdentifier($this->createIdentifier());
    }

    /**
     * Determine if the content of the FileConfigurationResource instance has changed.
     *
     * @return bool Returns True is the content has changed.
     */
    public function changed(): bool
    {
        return $this->getIdentifier() !== $this->createIdentifier();
    }

    /**
     * Create an identifier for the FileConfigurationResource instance.
     *
     * This identifier is used to map application settings values to an handler.
     *
     * @return string The identifier of the FileConfigurationResource instance.
     */
    protected function createIdentifier(): string
    {
        $identifier = null;
        $attributes = [];

        /** @var \SplFileInfo $file */
        $file = $this->getConfigurationResource()->getResource();

        $attributes['path']         = $file->getRealPath();
        $attributes['size']         = $file->getSize();
        $attributes['modified']     = $file->getMTime();
        $attributes['permission']   = $file->getPerms();

        if($data = json_encode($attributes)) {
            $identifier = md5($data);
        }

        if (!is_string($identifier)) {
            throw new \RuntimeException('Unable to create identifier for resource handler.');
        }

        return $identifier;
    }

    /**
     * Read en retreive the content of the FileConfigurationResource instance.
     *
     * @return null|array On success return the content of the FileConfigurationResource instance.
     */
    public function getContent(): ?array
    {
        return $this->getStrategy()->getEntries($this->getConfigurationResource());
    }
}
