<?php

namespace JelteV\ApplicationConfiguration\Resources\Validation;

/**
 * Ensure all Resource validators implements these functionalities.
 */
interface ResourceValidatorInterface
{
    /**
     * Validate the given resource for being an valid resource.
     *
     * @param mixed $resource The resource to validate.
     * @return bool Returns True if the resource is a valid resource.
     */
    public function validate($resource): bool;
}
