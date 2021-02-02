<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\Files\FileResourceHandler as FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class JsonHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * @return null|object
     */
    protected function getResourceContent(): ? array
    {
        $file       = $this->getResource();
        $content    = null;

        try {
            if ($content = file_get_contents($file->getRealPath())) {
                // @todo: check what happens if we directly parse the json output to an array.
                // json_decode($content, true)
                if ($data = json_decode($content, true)) {
                    $content = $data;
                }
            }
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

    public static function getExtensions(): array
    {
        return ['json'];
    }
}
