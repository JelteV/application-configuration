<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\Xml;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files\AbstractHandlerFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\Files\ResourceHandler as FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class HandlerFactory extends AbstractHandlerFactory
{
    protected function produceHandler(\SplFileInfo $file): ?ResourceHandlerInterface
    {
        $handler    = null;

        try {
            $xml = simplexml_load_file($file->getRealPath(), 'SimpleXMLElement', LIBXML_NOBLANKS);

            if ($xml !== false) {
                $handler = new FileResourceHandler($file, null);
            }
        } catch (\Exception $e) {
            $handler = null;
        }

        return $handler;
    }
}
