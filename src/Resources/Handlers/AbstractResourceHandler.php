<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyInterface;
use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 * Implementation of all the features all resource handlers use.
 */
abstract class AbstractResourceHandler implements ResourceHandlerInterface
{
    /**
     * The configuration resource to handle.
     *
     * @var ConfigurationResourceInterface $configurationResource
     */
    private ConfigurationResourceInterface $configurationResource;

    /**
     * The resource handler strategy to read the content of the ConfigurationResourceInterface instance.
     *
     * @var HandlerStrategyInterface $strategy
     */
    private HandlerStrategyInterface $strategy;

    /**
     * The identifier of the ConfigurationResourceInterface
     *
     * @var string $identifier
     */
    private string $identifier;

    /**
     * Initialize a new resource handler instance.
     *
     * @param ConfigurationResourceInterface $configurationResource The ConfigurationResourceInterface to handle.
     * @param HandlerStrategyInterface $strategy The strategy to read the content of the ConfigurationResourceInterface.
     */
    public function __construct(ConfigurationResourceInterface $configurationResource, HandlerStrategyInterface $strategy)
    {
        $this->configurationResource = $configurationResource;
        $this->strategy = $strategy;
    }

    /**
     * Get the identifier of the ConfigurationResourceInterface instance.
     *
     * @return string The identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set the identifier of the ConfigurationResourceInterface instance.
     *
     * @param string $identifier The identifier of the ConfigurationResourceInterface.
     */
    protected function setIdentifier(string $identifier)
    {
        if (!isset($this->identifier)) {
            $this->identifier = $identifier;
        }
    }

    /**
     * Get the resource handler strategy to read the content of the ConfigurationResourceInterface instance.
     *
     * @return HandlerStrategyInterface The Resource handler strategy instance.
     */
    protected function getStrategy(): HandlerStrategyInterface
    {
        return $this->strategy;
    }

    /**
     * Get the specified ConfigurationResource instance..
     *
     * @return ConfigurationResourceInterface The ConfigurationResourceInterface instance.
     */
    protected function getConfigurationResource(): ConfigurationResourceInterface
    {
        return $this->configurationResource;
    }

    /**
     * Create an identifier for the ConfigurationResource instance.
     *
     * This identifier is used to map application settings values to an handler.
     *
     * @return string The identifier of the ConfigurationResource instance.
     */
    abstract protected function createIdentifier(): string;

    /**
     * Get the content of the ConfigurationResource instance.
     *
     * @return null|array On success returns the content of the ConfigurationResource instance.
     */
    abstract public function getContent(): ? array;
}
