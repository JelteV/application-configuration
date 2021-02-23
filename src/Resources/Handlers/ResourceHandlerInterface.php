<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers;

/**
 * Ensure all resource handlers implements these functionalities.
 */
interface ResourceHandlerInterface
{
    /**
     * Get the identifier of the ConfigurationResourceInterface instance.
     *
     * @return string The identifier.
     */
    public function getIdentifier(): string;

    /**
     * Determine if the content of the ConfigurationResource instance has changed.
     *
     * @return bool Returns True is the content has changed.
     */
    public function changed(): bool;

    /**
     * Get the content of the ConfigurationResource instance.
     *
     * @return null|array On success returns the content of the ConfigurationResource instance.
     */
    public function getContent(): ?array;
}
