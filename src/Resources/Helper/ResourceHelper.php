<?php

namespace JelteV\ApplicationConfiguration\Resources\Helper;

class ResourceHelper
{
    public static function isPath($resource, bool $isRelativePath = true): bool
    {
        return static::isFilePath($resource, $isRelativePath) || static::isDirectoryPath($resource, $isRelativePath);
    }

    public static function isFilePath($resource, bool $isRelativePath = true): bool
    {
        // To find relative paths we need to convert it to an absolute path from the project working directory.
        $path = $isRelativePath ? static::createAbsolutePath($resource) : "{$resource}";

        return is_file($path);
    }

    public static function isDirectoryPath($resource, bool $isRelativePath = true): bool
    {
        // To find relative paths we need to convert it to an absolute path from the project working directory.
        $path = $isRelativePath ? static::createAbsolutePath($resource) : "{$resource}";

        return is_dir($path);
    }

    public static function createAbsolutePath($resource): string
    {
        $directory = static::getDocumentRootDirectory();

        return "{$directory}{$resource}";
    }

    private static function getDocumentRootDirectory(): string
    {
        return getcwd();
    }
}
