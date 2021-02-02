<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\Files\FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class YamlHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * @return mixed
     */
    protected function getResourceContent(): ? array
    {
        $file       = $this->getResource();
        $content    = null;

        try {
            if ($data = yaml_parse_file($file->getRealPath())) {
                $content = $data;
            }
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

    public static function getExtensions(): array
    {
        return ['yaml', 'yml'];
    }
}
