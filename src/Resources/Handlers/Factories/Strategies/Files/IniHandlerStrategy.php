<?php

namespace JelteV\ApplicationConfiguration\Resources\Handlers\Factories\Strategies\Files;

class IniHandlerStrategy extends AbstractFileHandlerStrategy
{
    /**
     * @return null|array
     */
    protected function getResourceContent(): ?array
    {
        $file       = $this->getResource();
        $content    = null;

        try {
            if ($data = parse_ini_file($file->getRealPath(), true, INI_SCANNER_TYPED)) {
                $content = $data;
            }
        } catch (\Exception $e) {
            $content = null;
        }

        return $content;
    }

    public static function getExtensions(): array
    {
        return ['ini'];
    }
}
