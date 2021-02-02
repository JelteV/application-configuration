<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies;

use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattener;
use JelteV\ApplicationConfiguration\Entries\Flattener\EnvConfigurationEntriesFlattener;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\EnvHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\IniHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\JsonHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\XmlHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files\YamlHandlerStrategy;
use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;

class HandlerStrategyFactory
{
    public function create($resource): ? HandlerStrategyInterface
    {
        $strategy = null;

        if (ResourceHelper::isPath($resource, false)) {
            $strategy = $this->createFileStrategy($resource);
        }

        return $strategy;
    }

    private function createFileStrategy(\SplFileInfo $file): HandlerStrategyInterface
    {
        $strategy  = null;
        $extension = $file->getExtension();

        if (in_array($extension, EnvHandlerStrategy::getExtensions(), true)) {
            $strategy = new EnvHandlerStrategy($file, new EnvConfigurationEntriesFlattener());
        } elseif (in_array($extension, IniHandlerStrategy::getExtensions(), true)) {
            $strategy = new IniHandlerStrategy($file, new ConfigurationEntriesFlattener());
        } elseif (in_array($extension, XmlHandlerStrategy::getExtensions(), true)) {
            $strategy = new XmlHandlerStrategy($file, new ConfigurationEntriesFlattener());
        } elseif (in_array($extension, YamlHandlerStrategy::getExtensions(), true)) {
            $strategy = new YamlHandlerStrategy($file, new ConfigurationEntriesFlattener());
        } elseif (in_array($extension, JsonHandlerStrategy::getExtensions(), true)) {
            $strategy = new JsonHandlerStrategy($file, new ConfigurationEntriesFlattener());
        } else {
            throw new \RuntimeException("{$extension} file is not supported");
        }

        return $strategy;
    }

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
