<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories;

interface ResourceHandlerFactoryInterface
{
    public function createHandlers($resource): ?array;
}
