<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\Json;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\AbstractHandlerFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\Files\ResourceHandler as FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class HandlerFactory extends AbstractHandlerFactory
{
    protected function produceHandler(\SplFileInfo $file): ?ResourceHandlerInterface
    {
        $handler  = null;

        try {
            $resource = fopen($file->getRealPath(), 'r');

            if (is_resource($resource)) {
                $handler = new FileResourceHandler($file, $resource);
            }
        } catch (\Exception $e) {
            $handler = null;
        }

        return $handler;
    }
}