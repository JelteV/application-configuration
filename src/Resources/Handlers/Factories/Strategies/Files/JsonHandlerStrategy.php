<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Entries\Flattener\ConfigurationEntriesFlattenerInterface;
use JelteV\ApplicationConfiguration\Resources\Resource\ConfigurationResourceInterface;

/**
 * Handler strategy to read and retrieve the content of an .json file.
 */
class JsonHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * Initialize a new JsonHandlerStrategy instance.
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
    protected function getResourceContent(ConfigurationResourceInterface $configurationResource): ? array
    {
        $file       = $configurationResource->getResource();
        $content    = null;

        try {
            if ($content = file_get_contents($file->getRealPath())) {
                if ($data = json_decode($content, true)) {
                    $content = $data;
                }
            }
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
        return ['json'];
    }
}
