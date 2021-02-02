<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers;

interface ResourceHandlerInterface
{
    public function getIdentifier(): string;

    public function changed(): bool;

    public function getContent();
}
