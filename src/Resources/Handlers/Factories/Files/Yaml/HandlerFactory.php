<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\Yaml;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\AbstractHandlerFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\Files\ResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class HandlerFactory extends AbstractHandlerFactory
{
    protected function produceHandler(\SplFileInfo $file): ?ResourceHandlerInterface
    {
        $handler = null;

        try {
            if (yaml_parse_file($file->getRealPath()) !== false) {
                $handler = new ResourceHandler($file, null);
            }
        } catch (\Exception $e) {
            $handler = null;
        }

        return $handler;
    }
}
