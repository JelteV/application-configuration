<?php

namespace JelteV\ApplicationConfiguration\Resources\Validation;

interface ResourceValidatorInterFace
{
    private function validate($resource): bool;
}