<?php

namespace JelteV\ApplicationConfiguration\Resources\Validation\Files;

use JelteV\ApplicationConfiguration\Resources\Validation\ResourceValidatorInterface;

/**
 * Validator determines if the validated resource is an valid file resource.
 */
class FileResourceValidator implements ResourceValidatorInterface
{
    /**
     * The file extension that needs to match with the extension of the specified resource.
     *
     * @var string
     */
    private string $extension;

    /**
     * Initialize a new FileResourceValidator instance.
     *
     * @param string $extension The file extension that needs to match with the extension of the specified resource.
     */
    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * Validate the given resource for being an valid file resource.
     *
     * @param mixed $resource The resource to validate.
     * @return bool Returns True if the resource is a valid file resource.
     */
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

    /**
     * Check if the given resource is a valid file.
     *
     * @param \SplFileInfo $resource The resource to check.
     * @return bool Returns True if the resource is a valid file.
     */
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

    /**
     * Check if the resource if an valid directory.
     *
     * @param \SplFileInfo $resource The resource to check.
     * @return bool Returns True if the given resource is an valid directory.
     */
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
