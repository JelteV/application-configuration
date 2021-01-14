<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Files;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\HandlerFactoryInterface;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;
use JelteV\ApplicationConfiguration\Resources\Validation\Files\ResourceValidator;
use JelteV\ApplicationConfiguration\Resources\Validation\ResourceValidatorInterface;

abstract class AbstractHandlerFactory implements HandlerFactoryInterface
{
    protected string $extension;

    protected ResourceValidatorInterface $validator;

    public function __construct(string $extension)
    {
        $this->setExtension($extension);
        $this->validator = new ResourceValidator($extension);
    }

    private function setExtension(string $extension)
    {
        if (preg_match('/^[a-zA-Z0-9]{2,5}$/m', $extension) !== 1) {
            throw new \InvalidArgumentException("Invalid value supplied as file extension, got: '{$extension}'");
        }

        $this->extension = $extension;
    }

    public function createHandlers($resource): array
    {
        $isPath         = is_string($resource);
        $isCollection   = is_array($resource);
        $resourceFiles  = [];

        if (!empty($resource) && ($isPath || $isCollection)) {
            $resourceFiles = $isCollection ? $resource : [$resource];
        }

        $handlers = [];

        foreach ($resourceFiles as $resourceFile) {
            if ($this->validator->validate($resourceFile)) {
                $handlers = array_merge($handlers, $this->produceHandlers(new \SplFileInfo($resourceFile)));
            }
        }

        return $handlers;
    }

    private function produceHandlers(\SplFileInfo $resource): array
    {
        $handlers = [];
        $isFile = $resource->isFile() && !$resource->isDir();

        if ($isFile) {
            if ($handler = $this->produceHandler($resource)) {
                $handlers[] = $handler;
            }
        } else {
            $files = glob("{$resource}/*.{$this->extension}");

            foreach ($files as $file) {
                if ($handler = $this->produceHandler($file)) {
                    $handlers[] = $handler;
                }
            }
        }

        return $handlers;
    }

    abstract protected function produceHandler(\SplFileInfo $file): ? ResourceHandlerInterface;
}