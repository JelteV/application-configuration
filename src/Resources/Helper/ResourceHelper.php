<?php

namespace JelteV\ApplicationConfiguration\Resources\Helper;

/**
 * Helper class providing various resource path related checks and functionalities.
 */
class ResourceHelper
{
    /**
     * Check if the given resource is an resource path.
     *
     * A resource path could be an file path or an directory path.
     *
     * @param mixed $resource The resource to check.
     * @param bool $isRelativePath (optional) specify whether the give resource is an relative path or not.
     * @return bool Returns true if the given resource is an resource path.
     */
    public static function isPath($resource, bool $isRelativePath = true): bool
    {
        return static::isFilePath($resource, $isRelativePath) || static::isDirectoryPath($resource, $isRelativePath);
    }

    /**
     * Check if the given resource is an file path.
     *
     * @param mixed $resource The resource to check.
     * @param bool $isRelativePath (optional) specify whether the give resource is an relative path or not.
     * @return bool Returns true if the given resource is an file path.
     */
    public static function isFilePath($resource, bool $isRelativePath = true): bool
    {
        // To find relative paths we need to convert it to an absolute path from the project working directory.
        $path = $isRelativePath ? static::createAbsolutePath($resource) : "{$resource}";

        return is_file($path);
    }

    /**
     * Check if the given resource is an directory path.
     *
     * @param mixed $resource The resource to check.
     * @param bool $isRelativePath (optional) specify whether the give resource is an relative path or not.
     * @return bool Returns true if the given resource is an directory path.
     */
    public static function isDirectoryPath($resource, bool $isRelativePath = true): bool
    {
        // To find relative paths we need to convert it to an absolute path from the project working directory.
        $path = $isRelativePath ? static::createAbsolutePath($resource) : "{$resource}";

        return is_dir($path);
    }

    /**
     * Convert the given resource into an absolute path.
     *
     * @param mixed $resource The resource path.
     * @return string Returns the absolute path for the given resource.
     */
    public static function createAbsolutePath($resource): string
    {
        $directory = static::getDocumentRootDirectory();

        return "{$directory}{$resource}";
    }

    /**
     * Get the current working directory path.
     *
     * @return string The path of the current working directory.
     */
    private static function getDocumentRootDirectory(): string
    {
        return getcwd();
    }
}
