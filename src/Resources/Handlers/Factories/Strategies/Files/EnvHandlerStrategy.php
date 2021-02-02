<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

use JelteV\ApplicationConfiguration\Entries\Filters\EnvEntryCommentFilter;

class EnvHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * @return null|string[]
     */
    protected function getResourceContent(): ?array
    {
        $file       = $this->getResource();
        $content    = null;

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

    public static function getExtensions(): array
    {
        return ['env'];
    }
}
