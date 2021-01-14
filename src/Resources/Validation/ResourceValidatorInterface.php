<?php

namespace JelteV\ApplicationConfiguration\Resources\Validation;

interface ResourceValidatorInterface
{
    public function validate($resource): bool;
}