<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattenerInterface;
use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 * Handler strategy to read and retrieve the content of an .env file.
 */
class EnvHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * Initialize a new EnvHandlerStrategy instance.
     *
     * @param ConfigurationEntriesFlattenerInterface $entriesFlattener The entries flattener to process the application settings data.
     */
    public function __construct(ConfigurationEntriesFlattenerInterface $entriesFlattener)
    {
        parent::__construct($entriesFlattener);
    }

    /**
     * Read the content of the given resource.
     *
     * @param ConfigurationResourceInterface $configurationResource The ConfigurationResource to read the content for.
     * @return null|array On succes returns the content of the specified resource.
     */
    protected function getResourceContent(ConfigurationResourceInterface $configurationResource): ?array
    {
        $content    = null;
        $file       = $configurationResource->getResource();

        try {
            $fileHandler = fopen($file->getRealPath(), 'r');

            if (!is_resource($fileHandler)) {
                throw new \RuntimeException("Unable to open file: '{$file->getRealPath()}'");
            }

            while (($line = fgets($fileHandler)) !== false) {
                // @todo: note: at this point comments in the env file are included.
                $content[] = $line;
            }

            fclose($fileHandler);
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

    /**
     * Get the file extensions that handler strategy can handle.
     *
     * @return string[] The list of supported file extensions
     */
    public static function getExtensions(): array
    {
        return ['env'];
    }
}
