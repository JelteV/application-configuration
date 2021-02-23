<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories;

use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

/**
 * Ensure the resource handler factory implement these features.
 */
interface ResourceHandlerFactoryInterface
{
    /**
     * Create the resource handler(s) for the given resource.
     *
     * @param mixed $resource The resource to create the resource handlers(s) for.
     * @return null|ResourceHandlerInterface[] On success returns an list of resource handlers.
     */
    public function createHandlers($resource): ?array;
}
