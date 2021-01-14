<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;

class ResourceHandler implements ResourceHandlerInterface
{
    private string $identifier;

    private $resource;

    private \SplFileInfo $file;

    public function __construct(\SplFileInfo $file, $resource)
    {
        $this->file         = $file;
        $this->resource     = $resource;
        $this->identifier   = $this->createIdentifier($file);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getFilePath(): string
    {
        return $this->file->getRealPath();
    }

    public function changed(): bool
    {
        return $this->createIdentifier($this->file) !== $this->createIdentifier(new \SplFileInfo($this->file->getRealPath()));
    }

    private function createIdentifier(\SplFileInfo $file): string
    {
        $identifier = null;
        $attributes = [];

        $attributes['path']         = $file->getRealPath();
        $attributes['size']         = $file->getSize();
        $attributes['modified']     = $file->getMTime();
        $attributes['permission']   = $file->getPerms();

        if($data = json_encode($attributes)) {
            $identifier = md5($data);
        }

        if (!is_string($identifier)) {
            throw new \RuntimeException('Unable to create identifier for resource handler.');
        }

        return $identifier;
    }
}
