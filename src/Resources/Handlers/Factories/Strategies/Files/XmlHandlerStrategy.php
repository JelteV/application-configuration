<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

class XmlHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * @return null|array
     */
    protected function getResourceContent(): ? array
    {
        $file       = $this->getResource();
        $content    = null;

        try {
            if ($data = simplexml_load_file($file->getRealPath(), 'SimpleXMLElement', LIBXML_NOBLANKS)) {
                $content = (array) $data;
            }
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

//    protected function produceHandler(\SplFileInfo $file): ?ResourceHandlerInterface
//    {
//        $handler    = null;
//
//        try {
//            $xml = simplexml_load_file($file->getRealPath(), 'SimpleXMLElement', LIBXML_NOBLANKS);
//
//            if ($xml !== false) {
//                $handler = new FileResourceHandler($file, null);
//            }
//        } catch (\Exception $e) {
//            throw new \RuntimeException("Could not create handler for file: '{$file->getRealPath()}'");
//        }
//
//        return $handler;
//    }

    public static function getExtensions(): array
    {
        return ['xml'];
    }
}
