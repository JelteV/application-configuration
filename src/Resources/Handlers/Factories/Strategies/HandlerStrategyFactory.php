<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies;

use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattener;
use JelteV\ApplicationConfiguration\Entries\Flattener\EnvConfigurationEntriesFlattener;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\AbstractFileHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\EnvHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\IniHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\JsonHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\XmlHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\YamlHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;

/**
 * Factory to create a handler strategy for the given resource.
 */
class HandlerStrategyFactory
{
    /**
     * Create an strategy handler for the given resource.
     *
     * @param mixed $resource The resource to create and handler strategy for.
     * @return null|HandlerStrategyInterface On success returns an HandlerStrategyInterface instance.
     */
    public function create($resource): ? HandlerStrategyInterface
    {
        $strategy = null;

        if (ResourceHelper::isPath($resource, false)) {
            $strategy = $this->createFileStrategy($resource);
        }

        return $strategy;
    }

    /**
     * Create an handler strategy for the given file resource.
     *
     * @param \SplFileInfo $file The file to create the handler strategy for.
     * @return AbstractFileHandlerStrategy On success return an AbstractFileHandlerStrategy instance.
     */
    private function createFileStrategy(\SplFileInfo $file): AbstractFileHandlerStrategy
    {
        $strategy  = null;
        $extension = $file->getExtension();

        if (in_array($extension, EnvHandlerStrategy::getExtensions(), true)) {
            $strategy = new EnvHandlerStrategy(new EnvConfigurationEntriesFlattener(false));
        } elseif (in_array($extension, IniHandlerStrategy::getExtensions(), true)) {
            $strategy = new IniHandlerStrategy(new ConfigurationEntriesFlattener(true));
        } elseif (in_array($extension, XmlHandlerStrategy::getExtensions(), true)) {
            $strategy = new XmlHandlerStrategy(new ConfigurationEntriesFlattener(true));
        } elseif (in_array($extension, YamlHandlerStrategy::getExtensions(), true)) {
            $strategy = new YamlHandlerStrategy(new ConfigurationEntriesFlattener(true));
        } elseif (in_array($extension, JsonHandlerStrategy::getExtensions(), true)) {
            $strategy = new JsonHandlerStrategy(new ConfigurationEntriesFlattener(true));
        } else {
            throw new \RuntimeException("{$extension} file is not supported");
        }

        return $strategy;
    }

    /**
     * Get the extensions of the supported file types.
     *
     * @return string[] The list of supported file extensions.
     */
    public function getSupportedFileExtensions(): array
    {
        return array_merge(
            EnvHandlerStrategy::getExtensions(),
            IniHandlerStrategy::getExtensions(),
            XmlHandlerStrategy::getExtensions(),
            YamlHandlerStrategy::getExtensions(),
            JsonHandlerStrategy::getExtensions()
        );
    }
}
