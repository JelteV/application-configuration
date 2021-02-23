<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories;

use SplFileInfo;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;
use JelteV\ApplicationConfiguration\Resources\Resource\Files\FileConfigurationResource;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\AbstractFileHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\Files\FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;

/**
 * Create the ResourceHandler(s) for the given resource.
 */
class ResourceHandlerFactory implements ResourceHandlerFactoryInterface
{
    /**
     * Factory that produces strategies used to create handler for the supported resources.
     *
     * @var HandlerStrategyFactory $strategyFactory
     */
    private HandlerStrategyFactory $strategyFactory;

    /**
     * Create a new ResourceHandlerFactory instance.
     */
    public function __construct()
    {
        $this->strategyFactory = new HandlerStrategyFactory();
    }

    /**
     * Create the resource handler(s) for the given resource.
     *
     * @param mixed $resource The resource to create the resource handlers(s) for.
     * @return null|ResourceHandlerInterface[] On success returns an list of resource handlers.
     */
    public function createHandlers($resource): ?array
    {
        $handlers = null;

        if (ResourceHelper::isPath($resource)) {
            // When the given resource is an directory multiple files could be returned.
            if ($files = $this->getFileResourceHandlerPaths($resource)) {
                $handlers = $this->getFileResourceHandlers($files);
            }
        }

        return $handlers;
    }

    /**
     * Create the handler(s) for the given file(s).
     *
     * @param SplFileInfo[] $files The file(s) to create the handler(s) for.
     * @return null|ResourceHandlerInterface[] On success returns an list of resource handlers.
     */
    private function getFileResourceHandlers(array $files): ? array
    {
        $handlers = null;

        foreach ($files as $file) {
            /** @var AbstractFileHandlerStrategy $strategy */
            if ($strategy = $this->strategyFactory->create($file)) {
                $handlers[] = new FileResourceHandler(new FileConfigurationResource($file), $strategy);
            }
        }

        return $handlers;
    }

    /**
     * Get the file path of the given resource.
     *
     * When given resource is an directory it could contains multiple resources, so in this case the one or more
     * file paths are returned.
     *
     * @param mixed $resource The resource to get the file path(s) for.
     * @return null|SplFileInfo[] On succes returns an list of SplFileInfo instances.
     */
    private function getFileResourceHandlerPaths($resource): ?array
    {
        $filePaths = null;

        if (ResourceHelper::isFilePath($resource)) {
            $filePaths = [new SplFileInfo(ResourceHelper::createAbsolutePath($resource))];
        } elseif (ResourceHelper::isDirectoryPath($resource)) {
            $fileExtensions             = implode(',', $this->strategyFactory->getSupportedFileExtensions());
            $absoluteDirectoryPath      = ResourceHelper::createAbsolutePath(rtrim($resource, '/'));

            if ($files = glob("{$absoluteDirectoryPath}/*.{{$fileExtensions}}", GLOB_BRACE)) {
                foreach ($files as $file) {
                    $filePaths[] = new SplFileInfo($file);
                }
            }
        }

        return $filePaths;
    }
}
