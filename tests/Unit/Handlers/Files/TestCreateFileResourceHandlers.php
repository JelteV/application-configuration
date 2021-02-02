<?php

namespace JelteV\ApplicationConfiguration\Test\Unit\Handlers\Files;

use JelteV\ApplicationConfiguration\Module;
use JelteV\ApplicationConfiguration\Resources\Handlers\Factories\ResourceHandlerFactory;
use PHPUnit\Framework\TestCase;

class TestCreateFileResourceHandlers extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        new Module();
    }

    private function getPaths(): array
    {
        return [
            '/tests/resources/files/env/bookstore.env',
            '/tests/resources/files/xml/bookstore.xml',
            '/tests/resources/files/yaml/bookstore.yml',
            '/tests/resources/files/json/bookstore.json',
            '/tests/resources/files/env/',
        ];
    }

    public function testCreateHandlers()
    {
        $paths = $this->getPaths();
        $factory = new ResourceHandlerFactory();

        $success = null;
        $handlers = null;

        foreach ($paths as $path) {
            if (!empty($handlers = $factory->createHandlers($path))) {
                if (is_null($success) || $success) {
                    $success = true;
                } else {
                    $success = false;
                }
            }
        }

        $this->assertTrue($success);
    }
}
