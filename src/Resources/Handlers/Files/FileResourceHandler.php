<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\AbstractResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\AbstractFileHandlerStrategy;

class FileResourceHandler extends AbstractResourceHandler
{
    public function __construct(AbstractFileHandlerStrategy $strategy)
    {
        parent::__construct($strategy);
        $this->setIdentifier($this->createIdentifier());
    }

    public function changed(): bool
    {
        return $this->getIdentifier() !== $this->createIdentifier();
    }

    protected function createIdentifier(): string
    {
        $identifier = null;
        $attributes = [];

        /** @var \SplFileInfo $file */
        $file = $this->getStrategy()->getResource();

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

    public function getContent()
    {
        return $this->getStrategy()->getEntries();
    }
}
