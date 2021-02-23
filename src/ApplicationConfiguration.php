<?php

namespace JelteV\ApplicationConfiguration;

use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\ResourceHandlerFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;
use JelteV\ApplicationConfiguration\Resources\Helper\ResourceHelper;

class ApplicationConfiguration
{
    /**
     * Factory to create resource handlers to read the content of the loaded resources.
     *
     * @var ResourceHandlerFactory $resourceHandlerFactory
     */
    private ResourceHandlerFactory $resourceHandlerFactory;

    /**
     * The resources to load application settings from.
     *
     * @var array $resources
     */
    private array $resources;

    /**
     * The loaded settings.
     *
     * @todo: replace the array by some sort of collection.
     *
     * @var array $settings
     */
    private array $settings;

    /**
     * Initialize a new ApplicationConfiguration instance.
     *
     * @param null|string $working_dir (optional) when invoked from CLI provide the current working directory.
     */
    public function __construct(string $working_dir = null)
    {
        $this->initialize($working_dir);

        $this->resourceHandlerFactory = new ResourceHandlerFactory();
        $this->settings = [];
    }

    /**
     * Initialize the required module settings.
     *
     * @param string|null $directory (optional) The path of current working directory.
     */
    private function initialize(string $directory = null)
    {
        // determine if the module is called for an CLI of an web-app environment.
        // @todo: make this more flexible, will do for development.
        $rootDirectory = php_sapi_name() === 'cli'
            ? $directory
            : $_SERVER['DOCUMENT_ROOT'];


        $this->setProjectRootDirectory($rootDirectory);
    }

    /**
     * Set the project root directory.
     *
     * @param string $directory The directory path to set as project root directory.
     */
    private function setProjectRootDirectory(string $directory)
    {
        if (!ResourceHelper::isDirectoryPath($directory, false)) {
            throw new \RuntimeException("Invalid value supplied as working directory path, got: {$directory}");
        }

        // Change current the directory path.
        if (!chdir($directory)) {
            throw new \RuntimeException("Could not change working directory to {$directory}");
        }
    }

    /**
     * Set the resource(s) to load.
     *
     * @param array $resources The resource(s) to load.
     */
    private function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * Load the application settings of the given resource(s).
     *
     * @param array $resources The resource(s) to load.
     * @return bool Returns true when the resources are loaded.
     */
    public function load(array $resources): bool
    {
        $this->setResources($resources);
        $handlers = $this->createResourceHandlers();

        try {
            foreach ($handlers as $handler) {
                if ($content = $handler->getContent()) {
                    $this->settings = array_merge($this->settings, $content);
                }
            }

            $loaded = true;
        } catch (\Exception $e) {
            $loaded = false;
        }

        return $loaded;
    }

    /**
     * Get the loaded application settings.
     *
     * @return array Returns the list of loaded application settings.
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Create resources handlers for the provided resources.
     *
     * @return ResourceHandlerInterface[] Returns a list of resource handlers.
     */
    private function createResourceHandlers(): array
    {
        $resourceHandlers = [];

        // @todo: keep track of loaded resources, prevent loading same (unchanged) resource(s) more than once.
        // See resource identifier etc. When doing so keep refreshing in mind.
        foreach ($this->resources as $resource) {
            if ($handlers = $this->resourceHandlerFactory->createHandlers($resource))
            $resourceHandlers = array_merge($handlers, $resourceHandlers);
        }

        return $resourceHandlers;
    }
}
