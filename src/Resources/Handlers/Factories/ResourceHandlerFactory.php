<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories;

use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;
use SplFileInfo;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\AbstractFileHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\HandlerStrategyFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\Files\FileResourceHandler;
use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;


class ResourceHandlerFactory implements ResourceHandlerFactoryInterface
{
    /**
     * Factory that produces strategies used to create handler for the supported resources.
     *
     * @var HandlerStrategyFactory $strategyFactory
     */
    private HandlerStrategyFactory $strategyFactory;

    public function __construct()
    {
        $this->strategyFactory = new HandlerStrategyFactory();
    }

    /**
     * @param mixed $resource
     * @return null|ResourceHandlerInterface[]
     */
    public function createHandlers($resource): ?array
    {
        $handlers = null;

        if (ResourceHelper::isPath($resource)) {
            if ($files = $this->getFileResourceHandlerPaths($resource)) {
                $handlers = $this->getFileResourceHandlers($files);
            }
        }

        return $handlers;
    }

    /**
     * @param SplFileInfo[] $files
     * @return null|ResourceHandlerInterface[]
     */
    private function getFileResourceHandlers(array $files): ? array
    {
        $handlers = null;

        foreach ($files as $file) {
            /** @var AbstractFileHandlerStrategy $strategy */
            if ($strategy = $this->strategyFactory->create($file)) {
                $handlers[] = new FileResourceHandler($strategy);
            }
        }

        return $handlers;
    }

    /**
     * @param $resource
     * @return null|SplFileInfo[]
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
