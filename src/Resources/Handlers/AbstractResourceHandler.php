<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyInterface;

abstract class AbstractResourceHandler implements ResourceHandlerInterface
{
    private HandlerStrategyInterface $strategy;

    private string $identifier;

    public function __construct(HandlerStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    protected function setIdentifier(string $identifier)
    {
        if (!isset($this->identifier)) {
            $this->identifier = $identifier;
        }
    }

    protected function getStrategy(): HandlerStrategyInterface
    {
        return $this->strategy;
    }

    abstract protected function createIdentifier(): string;

    abstract public function getContent();
}
