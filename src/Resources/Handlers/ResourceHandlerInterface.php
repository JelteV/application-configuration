<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers;

interface ResourceHandlerInterface
{
    public function getIdentifier(): string

    public function getResource()

    public function changed(): bool
}
