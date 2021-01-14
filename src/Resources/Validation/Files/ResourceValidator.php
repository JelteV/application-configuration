<?php

namespace JelteV\ApplicationConfiguration\Resources\Validation\Files;

use JelteV\ApplicationConfiguration\Resources\Validation\ResourceValidatorInterface;

class ResourceValidator implements ResourceValidatorInterface
{
    private string $extension;

    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    public function validate($resource): bool
    {
        $valid = false;

        if (!empty($resource) && is_string($resource)) {
            try {
                $resourceFile = new \SplFileInfo($resource);
                $valid = $this->isFile($resourceFile) || $this->isDirectory($resourceFile);
            } catch (\Exception $e) {
                $valid = false;
            }
        }

        return $valid;
    }

    private function isFile(\SplFileInfo $resource): bool
    {
        try {
            $isFile         = !$resource->isDir() && $resource->isFile();
            $hasExtension   = $resource->getExtension() === $this->extension;
            $hasContent     = $resource->getSize() !== 0;
            $isReadable     = $resource->isReadable();

            $valid = $isFile && $hasExtension && $hasContent && $isReadable;
        } catch (\Exception $e) {
            $valid = false;
        }

        return $valid;
    }

    private function isDirectory(\SplFileInfo $resource): bool
    {
        try {
            $isDirectory            = false;
            $filesInDirectory       = glob("{$resource}/*.{$this->extension}");

            if (is_array($filesInDirectory)) {
                foreach ($filesInDirectory as $file) {
                    if ($this->isFile($file)) {
                        $isDirectory = true;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            $isDirectory = false;
        }

        return $isDirectory;
    }
}
