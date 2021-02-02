<?php

namespace JelteV\ApplicationConfiguration\Test\Unit\Handlers\Content;

use JelteV\ApplicationConfiguration\Module;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\ResourceHandlerFactory;
use JelteV\ApplicationConfiguration\Resources\Handlers\ResourceHandlerInterface;
use PHPUnit\Framework\TestCase;

class TestResourceContent extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        new Module();
    }

    /**
     * @return ResourceHandlerInterface[]
     */
    private function getResourceHandlers(): array
    {
        $factory = new ResourceHandlerFactory();

        $resourcesHandlers = array_merge(
            [],
            $factory->createHandlers('/tests/resources/files/env/bookstore.env'),
            $factory->createHandlers('/tests/resources/files/ini/bookstore.ini'),
            $factory->createHandlers('/tests/resources/files/xml/bookstore.xml'),
            $factory->createHandlers('/tests/resources/files/yaml/bookstore.yml'),
            $factory->createHandlers('/tests/resources/files/json/bookstore.json')
        );

        return $resourcesHandlers;
    }

    public function testRetrieveResourceContent()
    {
        $handlers = $this->getResourceHandlers();
        $found = null;

        foreach ($handlers as $handler) {
            if (!empty($content = $handler->getContent())) {
                if (is_null($found) || $found) {
                    $found = true;
                } else {
                    $found = false;
                }
            }
        }

        $this->assertTrue($found);
    }
}
